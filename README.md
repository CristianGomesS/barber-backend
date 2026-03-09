# 💈 BarberShop API

Backend robusto para gestão de barbearia, desenvolvido com **Laravel 11/12** seguindo as melhores práticas de arquitetura de software, como **Repository Pattern** e **Service Layer**.

## 🚀 Status do Projeto

### 1. Autenticação & Segurança
- [x] **Registro e Login de usuários** (Sanctum/JWT).
- [x] **Esqueci minha senha**: Geração de token aleatório de 5 dígitos.
- [x] **E-mail Transacional**: Envio de template Blade customizado (Dark/Gold).
- [x] **Reset de Senha**: Validação de token e alteração com `password_confirmation`.
- [x] **Múltiplos Perfis**: Diferenciação entre Cliente, Barbeiro e Admin.

### 2. Gestão de Dados (CRUDs)
- [x] **Usuários (Users)**: Cadastro e gerenciamento de perfis.
- [x] **Serviços (Services)**: Cadastro de itens (Corte, Barba, Combo) com preço e tempo.
- [x] **Agendamentos (Appointments)**: Estrutura base de vinculação cliente/serviço.
- [x] **Disponibilidade**: Lógica de bloqueio de horários ocupados.

## 🏗️ Arquitetura
O projeto utiliza uma separação clara de responsabilidades para facilitar a escalabilidade:
* **Controllers:** Gerenciam as rotas e respostas HTTP.
* **Services:** Camada de **Regra de Negócio** (onde a mágica acontece).
* **Repositories:** Abstração total da camada de persistência (Eloquent).
* **Views (E-mail):** Templates responsivos com imagens via CID para evitar bloqueios em clientes de e-mail.



---

## 🛠️ Tecnologias
* **Linguagem:** PHP 8.4
* **Framework:** Laravel 11/12
* **E-mail:** Mailtrap (Sandbox)
* **Banco de Dados:** MySQL/PostgreSQL

---

## 🔧 Configuração Local

1. **Clonar o repositório:**
   ```bash
   git clone [https://github.com/CristianGomesS/barber-backend.git](https://github.com/CristianGomesS/barber-backend.git)