import yfinance as yf

def get_financial_data(ticker):
    print(f"👩‍💼 Júlia: Coletando dados financeiros de {ticker}...")
    
    # Adiciona .SA se for ação brasileira e não tiver
    if not ticker.endswith('.SA') and len(ticker) <= 5:
        ticker = ticker + '.SA'
    
    try:
        stock = yf.Ticker(ticker)
        hist = stock.history(period="1mo") # Pega 1 mês de história
        
        # Pega o preço atual (fechamento mais recente)
        current_price = hist['Close'].iloc[-1]
        
        # Calcula variação simples (início do mês vs agora)
        start_price = hist['Close'].iloc[0]
        variation = ((current_price - start_price) / start_price) * 100
        
        return f"Preço Atual: R$ {current_price:.2f}. Variação no mês: {variation:.2f}%."
        
    except Exception as e:
        return f"Erro ao coletar dados financeiros: {str(e)}"

# Teste rápido se rodar o arquivo direto
if __name__ == "__main__":
    print(get_financial_data("PETR4"))