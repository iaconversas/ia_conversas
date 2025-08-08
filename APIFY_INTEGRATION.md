# Integração Apify - Lead Hunter

Esta documentação descreve como configurar e usar a nova integração com a API da Apify no sistema Lead Hunter.

## Configuração

### 1. Variáveis de Ambiente

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# Apify Integration
APIFY_TOKEN=seu_token_apify_aqui
APIFY_GOOGLE_MAPS_ACTOR_ID=compass/google-maps-scraper
```

### 2. Obter Token da Apify

1. Acesse [https://apify.com](https://apify.com)
2. Crie uma conta ou faça login
3. Vá para Settings > Integrations
4. Copie seu API Token
5. Cole no arquivo `.env` na variável `APIFY_TOKEN`

## Funcionalidades Implementadas

### 1. Serviço ApifyService

Localizado em `app/Services/ApifyService.php`, este serviço gerencia todas as interações com a API da Apify:

- **runGoogleMapsActor()**: Inicia uma execução do ator Google Maps
- **getRunStatus()**: Verifica o status de uma execução
- **getRunResults()**: Obtém os resultados de uma execução concluída
- **processResults()**: Processa e normaliza os dados retornados
- **testConnection()**: Testa a conexão com a API
- **listActors()**: Lista atores disponíveis

### 2. Rotas da API

As seguintes rotas foram adicionadas:

- `POST /client/apify/start-capture` - Inicia captura de leads
- `GET /client/apify/run-status/{runId}` - Verifica status da execução
- `GET /client/apify/run-results/{runId}` - Obtém resultados
- `GET /client/apify/test-connection` - Testa conexão
- `GET /client/apify/list-actors` - Lista atores disponíveis

### 3. Interface do Usuário

A interface do Lead Hunter foi atualizada com:

- **Status da Integração**: Mostra se a conexão com Apify está ativa
- **Teste de Conexão**: Botão para verificar conectividade
- **Monitoramento em Tempo Real**: Acompanha o progresso da busca
- **Processamento Automático**: Normaliza telefones brasileiros e dados

## Como Usar

### 1. Verificar Conexão

1. Acesse a página Lead Hunter
2. Verifique o status da integração Apify no topo
3. Clique em "Testar Conexão" se necessário

### 2. Capturar Leads

1. Digite sua query de busca (ex: "restaurantes em São Paulo")
2. Defina o limite de resultados (1-100)
3. Clique em "Iniciar Busca"
4. Aguarde o processamento (pode levar alguns minutos)
5. Visualize os resultados na tabela

### 3. Exportar Dados

1. Use os filtros para refinar os resultados
2. Clique em "Download CSV" para exportar

## Processamento de Dados

O sistema automaticamente:

- **Normaliza telefones brasileiros**: Converte para formato +55 (XX) XXXXX-XXXX
- **Valida e-mails**: Verifica formato válido
- **Limpa URLs**: Remove protocolos desnecessários
- **Extrai informações**: Nome, telefone, e-mail, website, endereço, avaliações

## Exemplos de Busca

- "restaurantes em São Paulo"
- "dentistas em Rio de Janeiro"
- "academias em Belo Horizonte"
- "salões de beleza em Brasília"
- "oficinas mecânicas em Curitiba"
- "pet shops em Fortaleza"

## Limitações

- Máximo de 100 resultados por busca
- Tempo limite de 5 minutos por execução
- Dependente da disponibilidade da API Apify
- Requer token válido da Apify

## Troubleshooting

### Erro de Conexão
- Verifique se o token APIFY_TOKEN está correto
- Confirme se há créditos suficientes na conta Apify
- Teste a conectividade com a internet

### Busca Falha
- Verifique se a query está bem formatada
- Reduza o número de resultados solicitados
- Tente novamente após alguns minutos

### Resultados Vazios
- Refine a query de busca
- Verifique se existem estabelecimentos na região pesquisada
- Tente termos de busca mais específicos

## Suporte

Para suporte técnico, verifique:
1. Logs do Laravel em `storage/logs/`
2. Console do navegador para erros JavaScript
3. Status da API Apify em [https://status.apify.com](https://status.apify.com)