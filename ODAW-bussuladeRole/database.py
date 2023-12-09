from pymongo import MongoClient
from bson.objectid import ObjectId  # Para lidar com IDs do MongoDB


def get_db():
    client = MongoClient("mongodb://localhost:27017/")
    db = client["odaw-trabalho"]
    return db

def add_user(user_data):
    db = get_db()
    users = db["usuario"]
    users.insert_one(user_data)

def check_user_credentials(email, senha):
    db = get_db()
    users = db["usuario"]
    
    # Buscar usuário pelo email
    user = users.find_one({"email": email})
    
    # Verificar se o usuário existe e a senha está correta
    if user and user['senha'] == senha:
        return True
    else:
        return False
    
def get_user_info(email):
    db = get_db()
    users = db["usuario"]
    
    # Buscar usuário pelo email
    user = users.find_one({"email": email})
    
    return user  # Retorna o documento do usuário ou None se não encontrado

def add_novo_role(novo_role):
    db = get_db()
    roles = db["role"]
    roles.insert_one(novo_role)

def get_roles():
    db = get_db()
    roles = db["role"]
    return list(roles.find())

def get_usuarios():
    db = get_db()
    users = db["usuario"]
    return list(users.find())
