# Usa uma imagem leve do Python
FROM python:3.10-slim

# Define a pasta de trabalho
WORKDIR /app

# Copia os arquivos de requisitos e instala
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copia o código dos agentes
COPY . .

# Comando para rodar o worker automaticamente
CMD ["python", "-u", "worker.py"]