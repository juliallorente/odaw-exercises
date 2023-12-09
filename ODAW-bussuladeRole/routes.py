from flask import render_template, request, jsonify, session, redirect, url_for
from database import add_user, get_user_info, check_user_credentials, get_roles, add_novo_role, get_db, get_usuarios
from bson.objectid import ObjectId  # Importa ObjectId
from werkzeug.utils import secure_filename
from pymongo import ReturnDocument
from bson import ObjectId, json_util
import json
import os
import logging

logging.basicConfig(level=logging.DEBUG)

# Função para converter ObjectId para string
def objectIdToStr(o):
    if isinstance(o, ObjectId):
        return str(o)
    raise TypeError

def register_routes(app):
    
    @app.route('/')
    def index():
        return render_template('index.html')

    @app.route('/registro', methods=['POST'])
    def register_user():
        user_data = dict(request.form)
        if not all(field in user_data for field in ['nome', 'email', 'senha']):
            return jsonify({"error": "Todos os campos são obrigatórios!"}), 400
        add_user(user_data)
        return redirect(url_for('index', message='success'))

    @app.route('/login', methods=['POST'])
    def login():
        email = request.form['emaillogin']
        senha = request.form['senhalogin']

        if check_user_credentials(email, senha):
            user_info = get_user_info(email)  # Obtém os dados do usuário
            
            user_info_clean = {k: str(v) if isinstance(v, ObjectId) else v for k, v in user_info.items()}

            session['user_name'] = (user_info['nome'])  # Armazena o user_id na sessão
            session['user_info'] = user_info_clean  # Armazena user_info na sessão
            roles = get_roles()  # Obtém a lista de roles
            users = get_usuarios()
            return render_template('home.html', user_info=user_info, roles=roles, users=users)
        else:
            # Tratar caso de falha no login
            return redirect(url_for('index', message='errorlogin'))

    @app.route('/home')
    def home():
        if 'user_info' in session:
            user_info = session['user_info']  # Obtém todos os dados do usuário da sessão
            roles = get_roles()  # Obtenha a lista de roles
            usuarios = get_usuarios() #obter lista de usuarios
            return render_template('home.html', user_info=user_info, roles=roles, usuarios=usuarios)
        else:
            # Redirecionar para a página de login se o usuário não estiver logado
            return redirect(url_for('index'))
        
    @app.route('/logout')
    def logout():
        # Limpe a sessão
        session.clear()
        # Redirecione para a página de login
        return redirect(url_for('index'))

        try:
            if 'user_name' not in session:
                return jsonify({"message": "Usuário não está logado"}), 401

            user_name = session['user_name']
            db = get_db()
            users = db['usuario']
            
            converted_user_id = ObjectId(user_id)
            print("Converted User ID:", converted_user_id)  # Para depuração

            # Verifique se o documento existe
            user_document = users.find_one({"_id": converted_user_id})
            print("Role Document:", user_document)  # Para depuração
            
            result = users.find_one_and_update(
                {"_id": ObjectId(user_id)},
                {"$addToSet": {"seguidores": user_name}},
                return_document=ReturnDocument.AFTER
            )

            if result:
                return jsonify({"message": "Seguindo Usuario"}), 200
            else:
                return jsonify({"message": "Usuario não encontrado ou atualização falhou"}), 404
        except Exception as e:
            return jsonify({"message": "Erro ao seguir usuario: " + str(e)}), 500

    @app.route('/add-role', methods=['POST'])
    def add_role():
        url_para_a_imagem_salva = None  # Inicializa a variável como None

        if 'imagem_url' in request.files:
            file = request.files['imagem_url']
            if file.filename != '':
                filename = secure_filename(file.filename)
                filepath = os.path.join('static', 'uploads', filename)
                file.save(filepath)
                url_para_a_imagem_salva = url_for('static', filename='uploads/' + filename)

        novo_role = request.form.to_dict()
        if url_para_a_imagem_salva:
            novo_role['imagem_url'] = url_para_a_imagem_salva  # Adiciona a imagem apenas se ela existir



        add_novo_role(novo_role)
        return jsonify({"message": "Rolê criado com sucesso!"}), 201
    
    

    @app.route('/confirm-presence/<role_id>', methods=['POST'])
    def confirm_presence(role_id):
        try:
            if 'user_name' not in session:
                return jsonify({"message": "Usuário não está logado"}), 401

            user_name = session['user_name']
            db = get_db()
            roles = db['role']
            
            converted_role_id = ObjectId(role_id)
            print("Converted Role ID:", converted_role_id)  # Para depuração

            # Verifique se o documento existe
            role_document = roles.find_one({"_id": converted_role_id})
            print("Role Document:", role_document)  # Para depuração
            
            result = roles.find_one_and_update(
                {"_id": ObjectId(role_id)},
                {"$addToSet": {"confirmados": user_name}},
                return_document=ReturnDocument.AFTER
            )

            if result:
                return jsonify({"message": "Presença confirmada com sucesso"}), 200
            else:
                return jsonify({"message": "Rolê não encontrado ou atualização falhou"}), 404
        except Exception as e:
            return jsonify({"message": "Erro ao confirmar presença: " + str(e)}), 500

    @app.route('/desconfirmar-presenca/<role_id>', methods=['POST'])
    def desconfirmar_presenca(role_id):
        try:
            if 'user_name' not in session:
                return jsonify({"message": "Usuário não está logado"}), 401

            user_name = session['user_name']
            db = get_db()
            roles = db['role']
            
            converted_role_id = ObjectId(role_id)
            # Verifique se o documento existe
            role_document = roles.find_one({"_id": converted_role_id})
            print("Role Document:", role_document)  # Para depuração
            
            result = roles.find_one_and_update(
                {"_id": ObjectId(role_id)},
                {"$pull": {"confirmados": user_name}},
                return_document=ReturnDocument.AFTER
            )

            if result:
                return jsonify({"message": "Presença desconfirmada com sucesso"}), 200
            else:
                return jsonify({"message": "Rolê não encontrado ou atualização falhou"}), 404
        except Exception as e:
            return jsonify({"message": "Erro ao desconfirmar presença: " + str(e)}), 500

    @app.route('/verificar-presenca')
    def verificar_presenca():
        if 'user_name' not in session:
            return jsonify({"message": "Usuário não está logado"}), 401

        user_name = session['user_name']
        db = get_db()
        roles = db['role']
        
        # Buscar todos os eventos
        todos_roles = roles.find({})

        # Verificar se o usuário confirmou presença para cada evento
        presenca = {str(role['_id']): user_name in role.get('confirmados', []) for role in todos_roles}
        
        return jsonify(presenca)
            
            
    @app.route('/get-roles-confirmados')
    def get_roles_confirmados():
        user_name = session.get('user_name')
        if not user_name:
            return jsonify({"message": "Usuário não está logado"}), 401

        db = get_db()
        roles = db['role']
        roles_confirmados = roles.find({"confirmados": user_name})

        roles_confirmados_lista = [
            {"nome": role["nome"], "id": str(role["_id"])}
            for role in roles_confirmados
        ]

        return jsonify({"rolesConfirmados": roles_confirmados_lista})
    

    @app.route('/editar-perfil', methods=['POST'])
    def editar_perfil():
        user_name = session.get('user_name')
        if not user_name:
            return jsonify({"message": "Usuário não está logado"}), 401

        # Obter os dados do formulário
        nome = request.form.get('nome')
        email = request.form.get('email')
        # Outros campos conforme necessário

        db = get_db()
        users = db['usuario']  # Ou o nome da sua coleção de usuários

        # Atualizar o documento do usuário no banco de dados
        result = users.update_one({"nome": user_name}, {"$set": {"nome": nome, "email": email}})
        
        if result.modified_count:
            return jsonify({"message": "Perfil atualizado com sucesso!"}), 200
        else:
            return jsonify({"message": "Erro ao atualizar o perfil"}), 500

    @app.route('/api/usuarios')
    def api_get_usuarios():
        db = get_db()
        usuarios = db['usuarios'].find()
        usuarios_lista = []

        for usuario in usuarios:
            usuario['_id'] = objectIdToStr(usuario['_id'])
            usuarios_lista.append(usuario)

        # Retorna os dados em formato JSON
        return jsonify(usuarios_lista)
    

    @app.route('/follow-user/<user_id>', methods=['POST'])
    def follow_user(user_id):
        try:
            if 'user_name' not in session:
                return jsonify({"message": "Usuário não está logado"}), 401

            user_name = session['user_name']
            db = get_db()
            users = db['usuario']
            
            converted_user_id = ObjectId(user_id)
            print("Converted User ID:", converted_user_id)  # Para depuração

            # Verifique se o documento existe
            user_document = users.find_one({"_id": converted_user_id})
            print("Role Document:", user_document)  # Para depuração
            
            result = users.find_one_and_update(
                {"_id": ObjectId(user_id)},
                {"$addToSet": {"seguidores": user_name}},
                return_document=ReturnDocument.AFTER
            )

            if result:
                return jsonify({"message": "Seguindo Usuario"}), 200
            else:
                return jsonify({"message": "Usuario não encontrado ou atualização falhou"}), 404
        except Exception as e:
            return jsonify({"message": "Erro ao seguir usuario: " + str(e)}), 500

    @app.route('/unfollow-user/<user_id>', methods=['POST'])
    def unfollow_user(user_id):
        try:
            if 'user_name' not in session:
                return jsonify({"message": "Usuário não está logado"}), 401

            user_name = session['user_name']
            db = get_db()
            users = db['usuario']
            
            converted_user_id = ObjectId(user_id)
            # Verifique se o documento existe
            user_document = users.find_one({"_id": converted_user_id})
            print("User Document:", user_document)  # Para depuração
            
            result = users.find_one_and_update(
                {"_id": ObjectId(user_id)},
                {"$pull": {"seguidores": user_name}},
                return_document=ReturnDocument.AFTER
            )

            if result:
                return jsonify({"message": "Você deixou de seguir este usuario"}), 200
            else:
                return jsonify({"message": "Usuario não encontrado ou atualização falhou"}), 404
        except Exception as e:
            return jsonify({"message": "Erro ao deixar de seguir usuario: " + str(e)}), 500

    @app.route('/verificar-follow')
    def verificar_follow():
        if 'user_name' not in session:
            return jsonify({"message": "Usuário não está logado"}), 401

        user_name = session['user_name']
        db = get_db()
        users = db['usuario']
        
        # Buscar todos os eventos
        todos_users = users.find({})

        # Verificar se o usuário seguiu outro
        follow = {str(usuario['_id']): user_name in usuario.get('seguidores', []) for usuario in todos_users}
        
        return jsonify(follow)