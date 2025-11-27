Conheça o ListaFácil: Um Assistente Inteligente de Compras
O ListaFácil é uma aplicação web desenvolvida para resolver um problema comum: a desorganização na hora de fazer compras de supermercado. Diferente de um simples bloco de notas digital, o sistema foi projetado para otimizar o tempo do usuário, organizando os itens de forma inteligente.

1. Como ele funciona?
A ideia central é que o usuário possa criar suas listas em casa e, ao chegar no mercado, ter uma experiência guiada.

Organização por Corredores: O grande diferencial do sistema é o recurso de "Otimização". O usuário escolhe o supermercado e o sistema reorganiza a lista automaticamente, mostrando os itens na ordem exata dos corredores (do corredor 1 ao último). Isso evita aquele vai e vem desnecessário dentro da loja.

Controle de Gastos: Enquanto coloca os produtos no carrinho, o usuário pode marcar os itens (check) e somar o valor total em tempo real, ajudando a não estourar o orçamento.

2. Tecnologias Utilizadas (Os Bastidores)
O projeto foi construído com uma arquitetura web sólida e leve, sem depender de ferramentas pesadas que deixariam o site lento.

No Front-end (O que o usuário vê): Foi utilizado o trio clássico da web: HTML5, CSS3 e JavaScript puro. O design é limpo e responsivo, focado na usabilidade em celulares (já que usamos a lista andando pelo mercado). A interatividade é feita de forma moderna: quando você clica para salvar algo, a página não recarrega inteira; tudo acontece instantaneamente usando uma técnica chamada AJAX (Fetch API).

No Back-end (O cérebro do sistema): Toda a lógica foi desenvolvida em PHP. O sistema funciona através de uma API própria: o site envia os dados (como um novo item da lista) e o PHP processa, salva e devolve a resposta. Isso deixa o sistema organizado e pronto para crescer.

Banco de Dados: Para guardar as informações dos usuários, listas e produtos, é utilizado o MySQL. O banco é relacional, o que permite cruzar dados complexos, como saber qual categoria de produto fica em qual corredor de um mercado específico.

3. Segurança e Boas Práticas
O ListaFácil não é apenas funcional, ele é seguro.

Senhas Protegidas: As senhas dos usuários nunca são salvas em texto puro. O sistema utiliza criptografia forte (password_hash) para garantir que, mesmo se o banco de dados for acessado, as senhas estejam ilegíveis.

Proteção de Dados: O código utiliza métodos de proteção (Prepared Statements) que impedem que invasores tentem manipular o banco de dados através dos formulários do site.
