FROM laravelsail/php83-composer

# Instale o sudo
RUN apt-get update && apt-get install -y sudo

# Instale as dependências necessárias para o Puppeteer e Chromium
RUN apt-get install -y \
    libnss3 \
    libatk1.0-0 \
    libatk-bridge2.0-0 \
    libcups2 \
    libxcomposite1 \
    libxrandr2 \
    libxdamage1 \
    libgbm-dev \
    libpango-1.0-0 \
    libasound2 \
    libxshmfence1 \
    wget \
    curl \
    chromium

# Instale o Node.js 20.x e npm
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Copie o package.json e package-lock.json para o contêiner
WORKDIR /var/www/html
COPY package.json package-lock.json /var/www/html/

# Instale as dependências do npm (incluindo o Puppeteer)
RUN npm install

# Outras configurações do Dockerfile conforme necessário
# ...

# Exponha a porta necessária
EXPOSE 9000
