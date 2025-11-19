import google.generativeai as genai
import os
from dotenv import load_dotenv

# Carrega as chaves
load_dotenv()

# Configura o Google Gemini
genai.configure(api_key=os.getenv("GEMINI_API_KEY"))

def write_article(ticker, financial_data, news_data):
    print(f"📝 Key: Analisando dados e escrevendo relatório sobre {ticker}...")

    try:
        model = genai.GenerativeModel('gemini-pro-latest')

        prompt = f"""
        Atue como Key, um analista financeiro sênior e direto.
        Analise a ação: {ticker}

        DADOS TÉCNICOS (Júlia):
        {financial_data}

        NOTÍCIAS DE MERCADO (Pedro):
        {news_data}

        Escreva um relatório curto (máximo 3 parágrafos) recomendando o investidor.
        Seja profissional, mas use uma linguagem acessível.

        REGRA OBRIGATÓRIA FINAL:
        Sua última linha DEVE ser apenas a palavra do veredito entre colchetes, assim:
        STATUS: [Compra] ou STATUS: [Venda] ou STATUS: [Neutro]
        """

        response = model.generate_content(prompt)
        return response.text
        
    except Exception as e:
        return f"Erro ao gerar texto com IA: {str(e)}"

# Teste rápido individual
if __name__ == "__main__":
    # Simulando dados que viriam dos outros agentes
    dados_fin = "Preço: R$ 35,00. Subiu 10% no mês."
    dados_news = "Empresa anunciou lucros recordes. Mercado está otimista."
    
    print(write_article("TESTE3", dados_fin, dados_news))