import sys
import requests
import re
import os
from dotenv import load_dotenv

# Importa nossos agentes
import julia
import pedro
import key

# Carrega configurações
load_dotenv()
API_URL = os.getenv("API_URL")

def extract_status(text):
    """Tenta encontrar o STATUS: [Veredito] no texto do Key"""
    match = re.search(r'STATUS:\s*\[(.*?)\]', text)
    if match:
        return match.group(1)
    return "Neutro" # Se não achar, assume Neutro

def run_analysis(ticker):
    print(f"\n--- 🚀 INICIANDO ANÁLISE PARA: {ticker.upper()} ---")

    # 1. JÚLIA (Dados Financeiros)
    financial_data = julia.get_financial_data(ticker)
    print(f"✅ Júlia terminou.")

    # 2. PEDRO (Notícias)
    news_data = pedro.get_news_data(ticker)
    print(f"✅ Pedro terminou.")

    # 3. KEY (Análise e Redação)
    content = key.write_article(ticker, financial_data, news_data)
    print(f"✅ Key terminou a redação.")

    # 4. Extrair o Veredito (Compra/Venda)
    status = extract_status(content)
    print(f"⚖️  Veredito identificado: {status}")

    # 5. ENVIAR PARA A API (Laravel)
    print("📡 Enviando para o banco de dados...")
    
    payload = {
        "ticker": ticker.upper(),
        "conteudo": content,
        "status": status
    }

    try:
        response = requests.post(API_URL, json=payload)
        
        if response.status_code == 201:
            print(f"🎉 SUCESSO! Conteúdo salvo no ID: {response.json().get('id')}")
        else:
            print(f"❌ ERRO NA API: {response.status_code} - {response.text}")
            
    except Exception as e:
        print(f"❌ Erro de conexão com a API: {e}")

if __name__ == "__main__":
    # Pergunta qual ação analisar
    ticker_input = input("Digite o ticker da ação (ex: PETR4, VALE3, AAPL): ").strip()
    
    if ticker_input:
        run_analysis(ticker_input)
    else:
        print("Nenhum ticker digitado.")