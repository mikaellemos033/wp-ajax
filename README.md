# WP Ajax
> plugin simples para carregamento de posts, via ajax

## Maneiras de usar 

```
$.ajax({
    url: base_url + '/load-posts-json',
    method: 'GET',
    success: function(data){
        var response = JSON.parse(data);
    }
})
```

### Resposta de Sucesso
> startus: 200

```
{
    "success: true,
    "message": "Posts carregados com sucesso",
    "posts": []
}
```

### Respota de falha
> status: 404
```
{
    "success": false,
    "message": "Nada encontrado!"
}
```

### Parâmetros 
> os parâmetros a serem usados são os mesmos passados na função get_posts
```
{

	"posts_per_page": 5,
	"offset": 0,
	"category": "",
	"category_name": "",
	"orderby": "date",
	"order": "DESC",
	"include": "",
	"exclude": "",
	"meta_key": "",
	"meta_value": "",
	"post_type": "post",
	"post_mime_type": "",
	"post_parent": "",
	"author": "",
	"author_name": ,
	"post_status": "publish",
	"suppress_filters": true, 

}
```


>Há um outro parâmetro chamado `load_meta` onde são passados as metas que você quer trazer, separando por `;`
e usando alguns atributos para o carregamento, onde todos possuem duas opções :true ou :false:
 * `unique` para carregar um unico parâmetro.
 * `post` para carregar uma relação de posts presente em um postmeta
 
se nenhuma dessas opções forem informadas, serão trazidas todas as metas presentes no post.

```
load_meta=meta_key|unique:true;relation_post|post:true
```

### Filtro por categoria

Para realizar uma consulta com base numa categoria, basta adicionar 2 parâmetros:
 * :taxonomy tipo da taxonomia
 * :taxonomy_terms valor da consulta, Ex: slug
 * :taxonomy_field campo referencia de consulta, por padrão o campo é o slug, mas pode ser passado qualquer campo presente na estrutura de taxonomy do wordpress
 
 ```
 taxonomy_type=post_types&taxonomy_terms=category-slug
 ```
