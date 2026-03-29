# 🐴 Sistema de Gestão de Vaquejada v1.0

Um sistema ágil, completo e profissional desenvolvido em **Laravel** para o gerenciamento de inscrições, senhas de corrida e controle rigoroso de caixa para grandes eventos de Vaquejada.

O sistema foi arquitetado para suportar alta demanda nas bilheterias, fornecer relatórios antifraude precisos e garantir a rapidez no momento dos cadastros, integrando-se nativamente com tecnologias de pagamento e equipamentos de pista.

---

## 🚀 Principais Funcionalidades (Versão 1.0)

- **Gestão Ágil de Competidores & Inscrições**: 
  - Cadastro de vaqueiros e bate-esteiras com busca inteligente (autocomplete/Select2) para não travar a fila.
  - Precificação automática de inscrições configurável pelo administrador.
- **Caixa Inteligente e Pix Nativo**:
  - **Múltiplos Gateways Instalados**: Geração instantânea de Códigos PIX (QR Code) suportando tanto **Asaas** quanto **PagSeguro (PagBank)**. O sistema faz *Polling* automático validando o pagamento na tela sem que o caixa precise atualizar a página.
  - **Calculadora de Troco**: Assistente visual de frente de caixa que calcula trocos em tempo real a partir de dinheiro vivo recebido.
- **Gerenciamento e Impressão de Senhas**:
  - Emissão de senhas com controle de disponibilidade (reaproveitamento automático de senhas canceladas).
  - Status dinâmico de prova (`Pendente`, `Correu`, `Boi Batido` e `Cancelado`).
  - **Botão Impressão Térmica**: Geração de Mini-Cupons Não Fiscais (80mm) perfeitos para impressoras POS Bluetooth enviando a via do inscrito na hora!
- **Painel de Configurações Dinâmicas**: 
  - Altere informações do parque, preço padrão das senhas e chaves/Tokes de APIs de pagamento sem tocar em uma linha de código.
- **Relatórios Gerenciais em PDF**:
  - Relatório de Caixa, Estatísticas de Senhas faturadas, Receitas Pendentes vs Pagas e controle unificado.

---

## 🔐 Perfis de Acesso e Segurança (RBAC)

O sistema possui proteção por níveis de papel, ideal para a correria do evento:

- 👑 **Administrador Master**: Acesso irrestrito a configurações, gateways de pagamento, cadastro de usuários e exclusões.
- 📝 **Secretário(a) / Caixa**: Acessa o painel diário de vendas. Cadastra vaqueiros, emite as senhas e controla o recebimento, mas é bloqueado das configurações de API e relatórios de métricas extremas.
- 🎙️ **Locutor / Pista**: Perfil focado na operação. Lê os competidores na tela e pode alterar unicamente os status de prova (`Correu`, `Boi Batido`), sem permissões para "Cancelar" vendas ou estornar dinheiro, mitigando falhas ou fraudes.

---

## 🛠️ Tecnologias Utilizadas

- **Core**: [Laravel 12.x](https://laravel.com/) (PHP 8.2+)
- **Banco de Dados**: [MySQL](https://www.mysql.com/)
- **Frontend**: [Bootstrap 5](https://getbootstrap.com/), Javascript Nativo, [Select2](https://select2.org/).
- **Pagamentos**: APIs Rest (`Asaas` e `PagSeguro Orders API`)
- **Documentos**: DomPDF (Geração de comprovantes, relatórios e Bobinas Térmicas).

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
   npm install && npm run build
   ```

3. **Configure as Variáveis de Ambiente**
   Faça uma cópia do arquivo de exemplo `.env.example` para `.env`:
   ```bash
   cp .env.example .env
   ```
   *Abra o arquivo `.env` para inserir as credenciais do seu banco de dados MySQL (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).*

4. **Gere a Chave Mestra da Aplicação**
   ```bash
   php artisan key:generate
   ```

5. **Construa as Tabelas e o Seed Inicial**
   Gere o banco de dados e a conta inaugural de acesso corporativo:
   ```bash
   php artisan migrate --seed
   ```

6. **Inicie o Servidor**
   ```bash
   php artisan serve
   ```
   **Dica para Redes Locais:** Em um evento, caso precise acessar de outros computadores na mesma rede Wi-Fi, rode `php artisan serve --host=0.0.0.0 --port=8000` e acesse pelo IP da máquina servidora.

### 🔑 Acesso Padrão (Seed)
- **Email**: `admin@admin.com`
- **Senha**: `12345678`

*(Lembre-se de alterar as credenciais no primeiro acesso e configurar seu Gateway Oficial de Pagamento na aba de Configurações).*

---
✅ **Produzido e lapidado para Alta Performance em Eventos Equestres.**
