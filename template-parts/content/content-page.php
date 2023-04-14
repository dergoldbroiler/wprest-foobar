<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content">
	<form>

		<input type="text" id="filter-input" placeholder="Stichwort">
	</form>


<div id="results"></div>

</div>
</article><!-- #post-<?php the_ID(); ?> -->



<script>

	let result = [];

	const fetchSingle = async (value) => {
		let pages = `https://foobar:8890/wp-json/wp/v2/pages?s=${value}`;
		let posts = `https://foobar:8890/wp-json/wp/v2/posts?s=${value}`;

		
		let pages_fetch = await fetch(pages);
		let pages_json = await pages_fetch.json();
		result['pages']=pages_json;

		let posts_fetch = await fetch(posts);
		let posts_json = await posts_fetch.json();
		result['posts']=posts_json;
		
		if(value.length){	
			return result.posts.map(element => {
				
				return `${element.post_title}<br>`
			})
		} 
		
		return '';
		
	}

	let inp = document.querySelector('#filter-input');

	inp.addEventListener('keyup',e => {
		fetchSingle(e.target.value).then(data => document.querySelector('#results').innerHTML = data);
	});




</script>
