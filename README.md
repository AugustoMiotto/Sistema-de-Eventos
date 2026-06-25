# SSDE - Sistema de Gestão de Eventos

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL_/_MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC04B?style=for-the-badge&logo=alpine.js)](https://alpinejs.dev)

## Sobre o Aplicativo

O **SSDE** (Sistema de Gestão de Eventos) é uma plataforma digital projetada para modernizar e centralizar a criação, divulgação e gestão de eventos institucionais e acadêmicos. 

A aplicação resolve um problema comum em ambientes educacionais e corporativos: a fragmentação da informação. Ao invés de inscrições por formulários soltos e divulgação por e-mails perdidos, o SSDE oferece um ecossistema unificado onde **Promotores** podem planejar eventos (definindo vagas, palestrantes, categorias e valores) e **Participantes** podem explorar uma vitrine interativa para garantir suas inscrições de forma rápida e segura.

O design do sistema foi meticulosamente alinhado com a identidade visual do **IFRS**, garantindo uma experiência de usuário (UX) familiar, institucional e altamente intuitiva.

---

## Por que escolhemos esta Stack Tecnológica?

A arquitetura do SSDE não foi escolhida por acaso. Cada tecnologia resolve um desafio específico de engenharia de software para garantir escalabilidade, segurança e manutenibilidade.

### Por que Laravel (PHP)?
O Laravel atua como o "cérebro" do servidor (Backend). Escolhemos este framework por diversos motivos estratégicos:
* **Segurança Nativa:** Proteção "out-of-the-box" contra injeção de SQL, ataques CSRF (Cross-Site Request Forgery) e XSS.
* **Eloquent ORM:** Uma ferramenta poderosa que transforma tabelas do banco de dados em objetos fluentes. Isso permitiu criar relacionamentos complexos (como um Evento que pertence a um Promotor e possui várias Inscrições) com código limpo e legível.
* **Soft Deletes:** O ecossistema Laravel facilita a exclusão lógica de registros. Quando um promotor "apaga" um evento, ele desaparece da vitrine, mas continua no banco de dados para auditoria, protegendo o histórico do sistema.
* **Padrão MVC:** Organização clara que separa a lógica de negócios (Controllers), as regras de dados (Models) e a interface (Views).

### Por que MySQL / MariaDB?
Para o banco de dados, a escolha natural foi um sistema relacional robusto como o MySQL/MariaDB.
* **Integridade Transacional (ACID):** Em um sistema de eventos com controle de **vagas limitadas**, o banco de dados relacional garante que duas pessoas não se inscrevam simultaneamente na mesma e última vaga.
* **Relacionamentos Sólidos:** A arquitetura do SSDE exige integridade referencial forte (ex: uma inscrição não pode existir sem um usuário e um evento válidos). As *foreign keys* do MariaDB asseguram que o banco de dados nunca fique com informações "órfãs".

---

## Bibliotecas e Ferramentas Essenciais

Além da base sólida, o sistema utiliza bibliotecas específicas para entregar uma experiência moderna no lado do cliente (Frontend) e acelerar o desenvolvimento:

###  Tailwind CSS
* **Motivo da escolha:** Diferente de frameworks de componentes prontos (como Bootstrap), o Tailwind é baseado em classes utilitárias. Isso nos deu total liberdade para aplicar o verde específico da paleta institucional e criar layouts exclusivos (como a tela de login dividida e o dashboard de cards) sem lutar contra estilos pré-definidos. O resultado é um CSS final extremamente leve.

### Alpine.js
* **Motivo da escolha:** Precisávamos de reatividade no frontend (como abrir modais flutuantes e fazer requisições AJAX para criar categorias dinamicamente) sem a necessidade de instalar frameworks pesados como Vue.js ou React. O Alpine.js permite injetar comportamento diretamente no HTML (`x-data`, `x-show`), mantendo o sistema rápido e o código concentrado.

### Laravel Breeze
* **Motivo da escolha:** O Breeze foi utilizado como um "starter kit" de autenticação. Ele gerou rapidamente a espinha dorsal de segurança do sistema (Login, Registro, Recuperação de Senha e Edição de Perfil). Nós customizamos profundamente essas telas geradas por ele para acomodar lógicas de negócio exclusivas, como o "Toggle" flexível para o usuário se declarar como Promotor no momento do cadastro.
