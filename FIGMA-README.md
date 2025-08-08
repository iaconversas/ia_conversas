# Implementação do Design do Figma Venus Dashboard

## Visão Geral

Este projeto implementa o design do Figma "Venus - Dashboard Builder 2021 (Free Version) (Community)" em uma aplicação Laravel. A implementação inclui componentes reutilizáveis, estilos CSS e layouts que seguem fielmente o design original do Figma.

## Arquivos Criados/Modificados

### Estilos CSS

- `resources/css/figma-styles.css`: Contém todas as variáveis CSS, estilos de componentes e utilitários específicos do design do Figma.

### Componentes Blade

- `resources/views/components/figma/medium-card.blade.php`: Componente de card médio reutilizável com suporte para diferentes tipos (earnings, security, safety, user, transactions, calendar).
- `resources/views/components/figma/user-profile.blade.php`: Componente de perfil de usuário com avatar, nome, localização e estatísticas de seguidores.
- `resources/views/components/figma/sidebar.blade.php`: Componente de barra lateral com logo e navegação.
- `resources/views/components/figma/sidebar-item.blade.php`: Item de navegação da barra lateral com suporte para ícones e estado ativo.
- `resources/views/components/figma/button.blade.php`: Botão estilizado de acordo com o design do Figma.

### Layouts e Páginas

- `resources/views/layouts/figma-app.blade.php`: Layout principal para páginas que seguem o design do Figma.
- `resources/views/figma-dashboard.blade.php`: Página de exemplo que implementa o dashboard do Figma Venus.

### Imagens

- `public/images/figma/fingerprint_icon.svg`: Ícone de impressão digital usado no card de segurança.
- `public/images/figma/button_background.svg`: Textura de fundo para botões.

## Como Usar

### Acessando o Exemplo

Após iniciar o servidor, acesse a rota `/figma-example` para ver a implementação do dashboard do Figma Venus.

```
http://localhost:8000/figma-example
```

### Usando os Componentes

#### Medium Card

```blade
<x-figma.medium-card 
    title="Título do Card" 
    body="Descrição do card" 
    value="$100.00"
    icon="nome_do_icone.svg"
    buttonText="Texto do Botão"
    buttonUrl="#"
    type="earnings" <!-- earnings, security, safety, user, transactions, calendar -->
/>
```

#### User Profile

```blade
<x-figma.user-profile 
    name="Nome do Usuário" 
    location="Localização" 
    avatar="/caminho/para/avatar.jpg" <!-- opcional -->
    followers="100" 
    following="50"
    coverImage="/caminho/para/capa.jpg" <!-- opcional -->
/>
```

#### Sidebar e Itens

```blade
<x-figma.sidebar>
    <x-figma.sidebar-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
        Dashboard
    </x-figma.sidebar-item>
    
    <x-figma.sidebar-item href="#" icon="chart-bar">
        Analytics
    </x-figma.sidebar-item>
</x-figma.sidebar>
```

#### Botão

```blade
<x-figma.button href="#">Texto do Botão</x-figma.button>

<!-- ou como botão de formulário -->
<x-figma.button type="submit">Enviar</x-figma.button>
```

## Personalização

### Cores

As cores principais do design estão definidas como variáveis CSS em `resources/css/figma-styles.css`. Você pode modificá-las para atender às necessidades do seu projeto:

```css
:root {
  --color-primary: #6C5DD3;
  --color-primary-light: rgba(108, 93, 211, 0.1);
  --color-success: #7FBA7A;
  --color-warning: #FFA2C0;
  --color-info: #3E7BFA;
  /* ... outras variáveis ... */
}
```

### Tipografia

O design utiliza a fonte Inter do Google Fonts, que é importada no arquivo `resources/views/partials/head.blade.php`.

## Estrutura do Layout

O layout principal (`figma-app.blade.php`) consiste em:

1. Uma barra lateral à esquerda com navegação
2. Uma área de conteúdo principal à direita

A página de dashboard é organizada em três seções principais:

1. Cards de estatísticas principais (3 cards em uma linha)
2. Cards de informações secundárias (4 cards em uma linha)
3. Gráfico de desempenho mensal e atividades recentes (2 cards em uma linha)

## Notas Adicionais

- Os componentes são responsivos e se adaptam a diferentes tamanhos de tela.
- Os ícones utilizados são do Heroicons, integrados através do componente `x-dynamic-component`.
- As cores e estilos seguem fielmente o design original do Figma Venus Dashboard.