# frontend/Dockerfile
FROM node:18

WORKDIR /app

# Copier uniquement les fichiers nécessaires pour installer les dépendances
COPY package*.json ./

# Nettoyer le cache npm et installer les dépendances
RUN npm cache clean --force && npm install

# Copier le reste des fichiers
COPY . .

# Exposer le port pour le serveur de développement
EXPOSE 5173

# Lancer le serveur de développement
CMD ["npm", "run", "dev", "--", "--host"]