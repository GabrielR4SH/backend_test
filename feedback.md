# Feedback
Esse documento visa coletar feedbacks sobre o teste de desenvolvimento. Desde o início do teste até a entrega do projeto.

## Antes de Iniciar o Teste

1 - Fale sobre suas primeiras impressões do Teste:
> O teste parece bem estruturado e focado em avaliações técnicas reais do Laravel, acredito que não vou ter grandes difuldades

2 - Tempo estimado para o teste:
> 1 Dia

3 - Qual parte você no momento considera mais difícil?
> Configurar os Testes unitarios

4 - Qual parte você no momento considera que levará mais tempo?
> Os Testes de Integração e as consultas de Agregação como grupamento por dados temporais (dia, semana, mês) 

5 - Por onde você pretende começar?
> Fazer as Migrações configurando o env e criando as Models, depois construir os controllers de Redrict e RedirectLog


## Após o Teste

1 - O que você achou do teste?
> É um projeto que requer conhecimento do framework, não foi tão dificil pra mim por conta da minha familiaridade com a tecnlogia. Demonstra bem as habilidades que um desenvolvedor Laravel pleno precisa dominar.

2 - Levou mais ou menos tempo do que você esperava?
> Sim, esperava terminar esse projeto em no máximo 4 horas e acabei levando 9 horas por conta dos imprevistos

3 - Teve imprevistos? Quais?
> Falta da extensão GMP habilitada (necessária para o Hashids), Configuração do php.ini para ativar extensões necessárias e conflitos de versão do PHP entre o sistema e requisitos do Laravel

4 - Existem pontos que você gostaria de ter melhorado?
> Eu gostaria de ter criado esse projeto com Docker porque seria muito mais facil de lidar com problemas de versão do framework e com as extensões do PHP.. Porém como o teste não solicitou Docker e nem a descrição da vaga citava o uso do Docker eu resolvi fazer o mais proximo possivel do que foi proposto no teste

5 - Quais falhas você encontrou na estrutura do projeto?
> Falta de configuração no AppServiceProvider - Bindings e registros necessários para o funcionamento do Hashids
> RouteServiceProvider com prefixo duplicado - Rotas estavam sendo registradas como /api/api/redirects devido a dupla prefixação
