# 🐴 Sistema de Gestão de Vaquejada

Um sistema completo e profissional desenvolvido em **Laravel** para o gerenciamento de inscrições, senhas de corrida e caixa para eventos de Vaquejada. 

O sistema foi arquitetado para fornecer relatórios rigorosos, controle anti-fraudes nos caixas e agilidade no momento dos cadastros durante a competição.

---

## 🚀 Principais Funcionalidades

- **Gestão de Competidores**: Cadastro de vaqueiros com Nome, CPF, Cidade e Representação.
- **Inscrições de Duplas**: Criação de inscrições vinculando um Vaqueiro a um Bate-esteira.
- **Controle de Caixas/Pagamentos**: Acompanhamento dinâmico do valor total da inscrição e do status de pagamento (Pago ou Pendente).
- **Gerenciamento de Senhas**: Emissão de senhas para as corridas.
  - As senhas acompanham um status dinâmico que dita o ritmo da competição (`Pendente`, `Correu`, `Boi Batido` e `Cancelado`).
  - **Relatórios Extrativos Anti-fraude**: O sistema exige o motivo e documenta quem cancelou a senha no momento em que alguém aperta o botão 'cancelado', ideal para monitoria de devoluções de dinheiro no caixa.
- **Relatórios Gerenciais em PDF**:
  - Emissão de *Senhas de Corrida* (comprovante impressso de cada vaqueiro). Senhas canceladas recebem grandes carimbos visuais de proteção.
  - *Relatório de Fechamento*: Contagem total de faturamento ignorando senhas invalidadas e estatísticas amplas do andamento do parque.

---

## 🔐 Perfis de Acesso e Segurança (RBAC)

O painel de senhas conta com proteção e restrição programática de papéis para garantir a segurança da equipe.

- 👑 **Administrador Master**: Tem acesso total a todos os painéis. Exclui contas antigas, recadastra novos ajudantes pelo *Gerenciamento de Usuários interno*, e visualiza relatórios de caixa.
- 📝 **Secretário(a)**: Acessa o painel do dia a dia. Cadastra vaqueiros, emite as senhas, aprova os pagamentos e edita inscrições. É automaticamente bloqueado de ver relatórios gerais faturados para manter a privacidade do Parque.
- 🎙️ **Locutor**: Perfil essencial configurado como *Somente Leitura*. Acompanha em tempo real as inscrições e status das senhas da grande tela para a pista, porém colunas de 'Ações' como alterar botões, editar status, deletar listas ou mesmo adicionar chaves são completamente dizimadas e impedidas de seu acesso para mitigar confusões.

---

## 🛠️ Tecnologias Utilizadas

- **Backend / Core**: [Laravel 12.x](https://laravel.com/) (PHP 8+)
- **Banco de Dados**: [MySQL](https://www.mysql.com/) ORM
- **Frontend (Painel)**: [Bootstrap 5](https://getbootstrap.com/), Blade Diretives e JavaScript Interativo nativo.
- **Utilitários e Extensões**: PDF Wrapper (Geração de relatórios com dompdf).

---

## 📦 Como Instalar e Rodar o Projeto

1. **Clone o repositório**
   ```bash
   git clone https://github.com/emanuelxenos/senhas_vaquejada.git
   cd senhas_vaquejada
   ```

2. **Instale as dependências do Ecossistema**
   ```bash
   composer install
   ```

3. **Configure as Variáveis de Ambiente**
   Faça uma cópia do arquivo de exemplo `.env.example` para `.env`:
   ```bash
   cp .env.example .env
   ```
   *E não esqueça de abrir o arquivo `.env` para inserir as credenciais ao seu banco de dados MySQL (nas variáveis `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).*

4. **Gere a Chave Mestra da Aplicação**
   ```bash
   php artisan key:generate
   ```

5. **Construa as Tabelas e o Primeiro Acesso Oficial (Migration & Seed)**
   Abra seu servidor de banco de dados e simplesmente use o comando abaixo com a tag `--seed`. O Laravel vai plantar a base de dados zerinha e gerar a sua primeira semente de usuário seguro (o **Admin Master** inagural):
   ```bash
   php artisan migrate --seed
   ```

6. **Inicie o Servidor**
   ```bash
   php artisan serve
   ```
   **Tudo liso!** A aplicação entrará na pista e estará rodando em tempo real na máquina. Entre nela e acesse o servidor.

### 🔑 Exemplo do Primeiro Login no Sistema Limpo
- **Email**: `admin@admin.com`
- **Senha**: `12345678`

*(Recomendamos profundamente logar de primeira na sua aba lateral segura e alterar este seu perfil para os nomes corretos do proprietário).*

---
✅ **Produzido com extrema rigidez tecnológica para Parques Competidores.**
