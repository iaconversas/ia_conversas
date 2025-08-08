# Implementação de Estilos do Figma

## Visão Geral

Este projeto implementa os estilos e componentes do design "Venus Dashboard Builder 2021" do Figma no projeto Laravel existente. Os estilos foram adaptados para funcionar com o framework Livewire e o sistema de componentes Blade.

## Arquivos Criados

### Estilos CSS
- `resources/css/figma-styles.css` - Contém todos os estilos extraídos do Figma

### Componentes Blade
- `resources/views/components/figma/medium-card.blade.php` - Componente de card médio
- `resources/views/components/figma/user-profile.blade.php` - Componente de perfil de usuário
- `resources/views/components/figma/sidebar.blade.php` - Componente de barra lateral
- `resources/views/components/figma/sidebar-item.blade.php` - Item da barra lateral
- `resources/views/components/figma/button.blade.php` - Botão estilizado
- `resources/views/components/figma-nav-link.blade.php` - Link de navegação para o exemplo

### Layouts
- `resources/views/components/layouts/figma-app.blade.php` - Layout alternativo usando o estilo Figma

### Páginas de Exemplo
- `resources/views/figma-dashboard.blade.php` - Dashboard de exemplo usando os componentes Figma

## Como Usar

### Acessar o Exemplo
Você pode acessar o exemplo do Figma através do link "Figma Example" na barra lateral da aplicação ou diretamente pela URL `/figma-example`.

### Usar os Componentes

#### Card Médio
```blade
<x-figma.medium-card 
    title="Título do Card" 
    body="Descrição do card" 
    value="$100.00"
    buttonText="Texto do Botão"
    buttonUrl="#"
/>
```

#### Perfil de Usuário
```blade
<x-figma.user-profile 
    name="Nome do Usuário" 
    location="Localização" 
    followers="100" 
    following="50"
/>
```

#### Botão
```blade
<x-figma.button href="#">
    Texto do Botão
</x-figma.button>
```

#### Usar o Layout Figma
```blade
<x-layouts.figma-app :title="__('Título da Página')">
    <!-- Conteúdo da página -->
</x-layouts.figma-app>
```

## Imagens
As imagens do Figma foram baixadas e salvas em `public/images/figma/`.

## Personalização
Você pode personalizar as cores e estilos editando o arquivo `resources/css/figma-styles.css`.