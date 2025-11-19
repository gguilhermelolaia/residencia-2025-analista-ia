import requests
import json
import os
from dotenv import load_dotenv

# Carrega as chaves do arquivo .env
load_dotenv()

def get_news_data(ticker):
    print(f"🕵️‍♂️ Pedro: Pesquisando notícias sobre {ticker}...")
    
    url = "https://google.serper.dev/search"
    
    # CORREÇÃO: Usando json.dumps para garantir aspas duplas
    payload = json.dumps({
        "q": f"{ticker} mercado financeiro notícias",
        "gl": "br",
        "hl": "pt-br"
    })
    
    headers = {
        'X-API-KEY': os.getenv('SERPER_API_KEY'),
        'Content-Type': 'application/json'
    }
    
    try:
        response = requests.request("POST", url, headers=headers, data=payload)
        
        # Se a API reclamar, mostra o erro exato
        if response.status_code != 200:
            return f"Erro na API Serper: {response.status_code} - {response.text}"

        results = response.json()
        
        # Pega as 3 primeiras notícias orgânicas
        news_list = []
        if 'organic' in results:
            for item in results['organic'][:3]:
                title = item.get('title', '')
                snippet = item.get('snippet', '')
                news_list.append(f"- {title}: {snippet}")
        
        if not news_list:
            return "Nenhuma notícia encontrada."
            
        return "\n".join(news_list)
        
    except Exception as e:
        return f"Erro ao buscar notícias: {str(e)}"

# Teste rápido
if __name__ == "__main__":
    print(get_news_data("PETR4"))