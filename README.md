# WP Ajax
> plugin simples para carregamento de posts, via ajax

## Maneiras de usar 

```
$.ajax({
    url: base_url + '',
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
    "failed": true,
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
	"suppress_filters": true 

}
```


>Há um outro parâmetro que é chamado `load_meta` onde são passados as metas que você quer trazer, separando por `;`
e usando `:true` para carregar um unico parâmetro.

```
load_meta=meta_key:true;other_key
```