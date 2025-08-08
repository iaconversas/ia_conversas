# Configuração do Supabase para Gerenciamento de Arquivos

Este documento explica como configurar o Supabase como serviço de armazenamento em nuvem para o sistema de gerenciamento de arquivos.

## Pré-requisitos

1. Conta no [Supabase](https://supabase.com)
2. Projeto criado no Supabase
3. Bucket de storage configurado

## Configuração do Supabase

### 1. Criar um Projeto no Supabase

1. Acesse [supabase.com](https://supabase.com) e faça login
2. Clique em "New Project"
3. Escolha sua organização e configure o projeto
4. Anote a URL do projeto e a chave anônima (anon key)

### 2. Configurar o Storage

1. No painel do Supabase, vá para "Storage"
2. Crie um novo bucket chamado `user-files` (ou outro nome de sua preferência)
3. Configure as políticas de acesso conforme necessário

### 3. Configurar as Variáveis de Ambiente

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# Supabase Configuration
SUPABASE_URL=https://your-project-id.supabase.co
SUPABASE_ANON_KEY=your-anon-key-here
SUPABASE_BUCKET=user-files
```

**Onde encontrar essas informações:**
- `SUPABASE_URL`: No painel do projeto, seção "Settings" > "API"
- `SUPABASE_ANON_KEY`: No painel do projeto, seção "Settings" > "API" > "Project API keys" > "anon public"
- `SUPABASE_BUCKET`: Nome do bucket criado no step 2

## Como Funciona

O sistema implementa um padrão de fallback:

1. **Prioridade**: Se o Supabase estiver configurado, todos os arquivos serão armazenados na nuvem
2. **Fallback**: Se o Supabase não estiver configurado ou falhar, o sistema usa o armazenamento local
3. **Transparência**: O usuário não percebe diferença na interface

## Funcionalidades Implementadas

### Upload de Arquivos
- Detecta automaticamente se o Supabase está configurado
- Faz upload para a nuvem quando disponível
- Fallback para armazenamento local em caso de falha

### Listagem de Arquivos
- Lista arquivos do Supabase quando configurado
- Combina com arquivos locais se necessário
- Mantém a mesma estrutura de categorias

### Exclusão de Arquivos
- Remove arquivos do Supabase quando configurado
- Fallback para exclusão local
- Log de erros para debugging

### Configuração Automática
- Detecta o tipo de storage configurado
- Cria estrutura de diretórios conforme necessário
- Retorna informações sobre o tipo de storage ativo

## Estrutura de Arquivos

Os arquivos são organizados da seguinte forma:

```
user-files/
└── user-media/
    └── {user_id}/
        ├── image/
        ├── video/
        ├── audio/
        └── other/
```

## Segurança

- Todos os uploads requerem autenticação via token de API
- Usuários só podem acessar seus próprios arquivos
- Validação de tipos de arquivo no frontend e backend
- Logs de segurança para tentativas de acesso não autorizado

## Troubleshooting

### Problema: Arquivos não são enviados para o Supabase

**Solução:**
1. Verifique se as variáveis de ambiente estão corretas
2. Confirme se o bucket existe no Supabase
3. Verifique os logs da aplicação para erros específicos

### Problema: Erro de permissão no Supabase

**Solução:**
1. Verifique as políticas RLS (Row Level Security) do bucket
2. Confirme se a chave anônima tem permissões adequadas
3. Teste a conectividade com o Supabase

### Problema: Arquivos aparecem duplicados

**Solução:**
1. Isso pode acontecer durante a migração
2. Limpe o cache da aplicação
3. Verifique se não há arquivos tanto no local quanto no Supabase

## Migração de Arquivos Existentes

Para migrar arquivos do armazenamento local para o Supabase:

1. Configure o Supabase conforme este guia
2. Os novos uploads irão automaticamente para o Supabase
3. Arquivos antigos permanecerão no armazenamento local até serem substituídos
4. Para migração completa, será necessário um script personalizado (não incluído)

## Monitoramento

O sistema registra logs importantes:

- Sucessos e falhas de upload
- Tentativas de acesso não autorizado
- Erros de conectividade com o Supabase
- Fallbacks para armazenamento local

Verifique os logs em `storage/logs/laravel.log` para debugging.