<!-- Improved compatibility of back to top link -->
<a id="readme-top"></a>

<!-- PROJECT SHIELDS -->
![Contributors][contributors-shield]
![Forks][forks-shield]
![Linkedin][linkedin-shield]

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/Matvops/ponto_eletronico">
    <img src="https://github.com/user-attachments/assets/aca6039e-b8b3-42eb-8ac4-96b8f2d8c391" alt="Logo" width="100%" height="70%">
  </a>

  <h3 align="center">Ponto Eletrônico</h3>

  <p align="center">
    Sistema de controle de ponto eletrônico desenvolvido em Laravel.
    <br />
    <a href="https://github.com/Matvops/ponto_eletronico"><strong>Explorar a documentação »</strong></a>
    <br />
    <br />
    <a href="https://github.com/Matvops/ponto_eletronico/issues/new?labels=bug&template=bug-report---.md">Reportar Bug</a>
    ·
    <a href="https://github.com/Matvops/ponto_eletronico/issues/new?labels=enhancement&template=feature-request---.md">Solicitar Funcionalidade</a>
  </p>
</div>

---

## 📌 Sobre o Projeto

<div align="center">
  <img width="80%" alt="Tela home admin" src="https://github.com/user-attachments/assets/2a874137-d647-4610-bc7b-392668ad1d78" />
</div>

O **Ponto Eletrônico** é um sistema de registro e gerenciamento de jornada de trabalho, desenvolvido em **Laravel**.  
O sistema oferece funcionalidades tanto para usuários comuns quanto para administradores, permitindo controle de horários.

### ✨ Funcionalidades principais
- Bater ponto (entrada e saída).
- Consultar histórico de pontos.
- Tela inicial com **gráfico dinâmico** baseado no saldo de horas (seta verde para cima ou vermelha para baixo).
- Cadastro, atualização e busca de usuários (com paginação e **Livewire**).
- Diferentes visualizações por nível de acesso (**ADMIN e USER**).
- Autenticação e autorização.
- Envio de e-mails para:
  - Verificação de conta
  - Recuperação de senha com código de verificação
- Manipulação de horas:
  - Administrador pode redefinir horas de um usuário
- Uso de **SweetAlert2** para modais interativos.
- **Commands no Kernel**:
  - Atualização automática de pontos diariamente
  - Consultas a API externa de feriados
- Testes unitários com **PHPUnit**.
- Cobertura de código monitorada com **Xdebug Dashboard**.
- Aplicação de **TDD** e **Clean Code**, no projeto.

<p align="right">(<a href="#readme-top">Voltar ao topo</a>)</p>

---


## 🛠️ Tecnologias Utilizadas
* ![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
* ![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
* ![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)
* ![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
* ![NPM](https://img.shields.io/badge/NPM-%23CB3837.svg?style=for-the-badge&logo=npm&logoColor=white)
* [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
* ![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
* ![Livewire](https://img.shields.io/badge/livewire-%234e56a6.svg?style=for-the-badge&logo=livewire&logoColor=white)
* [![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)](https://getcomposer.org/)
* ![Postgres](https://img.shields.io/badge/postgres-%23316192.svg?style=for-the-badge&logo=postgresql&logoColor=white)
* ![Visual Studio Code](https://img.shields.io/badge/Visual%20Studio%20Code-0078d7.svg?style=for-the-badge&logo=visual-studio-code&logoColor=white)

<p align="right">(<a href="#readme-top">Voltar ao topo</a>)</p>

---

## 🚀 Como começar

Essas instruções vão te ajudar a rodar o projeto localmente.

### ✅ Pré-requisitos
- PHP >= 8.2
- Composer
- PostgreSQL
- Node.js & NPM


### ⚙️ Instalação

1. Clone o repositório:
   ```sh 
   git clone https://github.com/Matvops/ponto_eletronico.git
2. Instale as dependências do PHP:
   ```sh 
   composer install
3. Instale os pacotes do NPM:
   ```sh 
   npm install && npm run build
4. Copie o arquivo de configuração e ajuste suas variáveis:
   ```sh 
   cp .env.example .env
5. Gere a chave da aplicação:
   ```sh 
   php artisan key:generate
6. Execute as migrations:
   ```sh 
   php artisan migrate
   
<p align="right">(<a href="#readme-top">Voltar ao topo</a>)</p>

### 📖 Uso

1. Após a instalação, rode o servidor Laravel:
   ```sh 
   php artisan app:start
  Acesse no navegador: http://localhost:8000 
<p align="right">(<a href="#readme-top">Voltar ao topo</a>)</p>

## 📷 Telas do Sistema

### Tela de Login
<img  width="70%" height="50%" alt="Imagem Login" src="https://github.com/user-attachments/assets/cc87dc88-d4f9-4b31-a313-dd32bc1cde89" />

### Esqueci minha senha
<img width="70%" alt="Captura de tela de 2025-09-16 10-47-10" src="https://github.com/user-attachments/assets/0ff37d33-bb67-4784-b6a6-b021d9fcc844" />
<img width="27%" src="https://github.com/user-attachments/assets/099e14b1-24d2-4906-9fbf-dbdddd90a0a5" />


### Tela Inicial com Gráfico (visualização usuário)
<img width="70%" alt="Tela inicial usuário" src="https://github.com/user-attachments/assets/8fcfc0ed-5cbd-40a5-a460-e881813ed22f" />

### Tela para bater ponto (visualização usuário)
<img width="70%" alt="Tela para bater ponto" src="https://github.com/user-attachments/assets/36306f01-53f8-44a2-9030-a2c6291155ec" />

### Tela visualização de histórico, com busca e filtros. (visualização usuário)
<img width="70%" alt="Tela de visualização de usuários" src="https://github.com/user-attachments/assets/8ce9dad9-32e2-42c9-9c74-b6238d31e588" />

### Tela Inicial com Sobre (visualização administrador)
<img width="70%" alt="Tela home do administrador" src="https://github.com/user-attachments/assets/617ed8b0-ec3a-4f0f-a12c-594fb00064db" />

### Visualizar usuários (visualização administrador)
<img width="70%" alt="Captura de tela de 2025-09-16 10-55-43" src="https://github.com/user-attachments/assets/b157e332-a879-4e2b-97b4-1457173e3ab5" />

### Atualizar usuário (visualização administrador)
<img width="70%" alt="Tela atualização de usuário" src="https://github.com/user-attachments/assets/805428e8-9ff0-4cf9-b41a-e050a2108510" />

### Cadastrar usuário (visualização administrador)
<img width="70%" alt="Tela cadastro usuário" src="https://github.com/user-attachments/assets/8a3e2661-e624-4d5a-9728-4d1b9170f07d" />


### 🖥️ Demo

https://github.com/user-attachments/assets/8a87bac0-1158-43d7-a8dc-f0541607f3b4





## 📌 Roadmap

- [x] Registro de ponto (entrada/saída)
- [x] Controle de usuários (cadastro, atualização e busca)
- [x] Diferentes níveis de acesso (Admin/User)
- [x] Integração com API de feriados
- [ ] Relatórios exportáveis (PDF/Excel)
- [ ] Painel administrativo avançado
- [ ] Controle de permissões (ACL)
- [ ] Internacionalização (i18n)

---

## 📬 Contato  

<div>
  <strong>Link do projeto:</strong> https://github.com/Matvops/ponto_eletronico
</div>
<div>
  <strong>Link do Linkedin:</strong> https://www.linkedin.com/in/matheus-cadenassi-799125321/
</div>

[contributors-shield]: https://img.shields.io/github/contributors/othneildrew/Best-README-Template.svg?style=for-the-badge
[contributors-url]: https://github.com/Matvops/ponto_eletronico/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/othneildrew/Best-README-Template.svg?style=for-the-badge
[forks-url]: https://github.com/Matvops/ponto_eletronico/forks
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://www.linkedin.com/in/matheus-cadenassi-799125321/
