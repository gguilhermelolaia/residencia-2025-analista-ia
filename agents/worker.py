import time
import requests
import os
from dotenv import load_dotenv
import julia
import pedro
import key
import re

load_dotenv()

# Ajuste de URL inteligente
default_url = "http://localhost:8000/api/conteudos"
API_LIST_URL = os.getenv("API_URL", default_url)
# Para salvar, vamos usar a mesma base
API_BASE_URL = os.getenv("API_URL", default_url).replace("/api/conteudos", "") + "/api/conteudos"

def extract_status(text):
    match = re.search(r'STATUS:\s*\[(.*?)\]', text)
    if match: return match.group(1)
    return "Neutro"

def processar_pendentes():
    print("📡 Buscando solicitações pendentes...")
    
    try:
        response = requests.get(API_LIST_URL)
        if response.status_code != 200:
            print("⚠️ Erro ao ler API.")
            return False

        todos = response.json()
        
        # Procura o culpado: Status 'Processando'
        pendente = next((item for item in todos if item['status'] == 'Processando'), None)
        
        if not pendente:
            return False 

        ticker = pendente['ticker']
        id_pendente = pendente['id']
        
        print(f"\n--- 🚀 PROCESSANDO PEDIDO: {ticker} (ID: {id_pendente}) ---")

        # --- TRABALHO DOS AGENTES ---
        dados_fin = julia.get_financial_data(ticker)
        dados_news = pedro.get_news_data(ticker)
        texto_final = key.write_article(ticker, dados_fin, dados_news)
        veredito = extract_status(texto_final)

        # --- A CORREÇÃO MÁGICA ---
        # Em vez de criar um novo, vamos ATUALIZAR o ID que encontramos
        payload = {
            "conteudo": texto_final,
            "status": veredito
        }
        
        # Manda um PUT para /api/conteudos/{ID}
        update_url = f"{API_BASE_URL}/{id_pendente}"
        put_response = requests.put(update_url, json=payload)
        
        if put_response.status_code == 200:
            print(f"✅ Pedido {id_pendente} ({ticker}) atualizado com sucesso!")
        else:
            print(f"❌ Erro ao atualizar: {put_response.text}")
        
        return True

    except Exception as e:
        print(f"❌ Erro crítico no worker: {e}")
        return False

if __name__ == "__main__":
    print("👷‍♂️ Worker IA iniciado e CORRIGIDO! Aguardando...")
    while True:
        encontrou = processar_pendentes()
        if not encontrou:
            time.sleep(5)