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

<style>
	.hidden {
		display:none
	}
	.inputcontainer {display:flex;
	
	}
.icon-container {
	margin-left:-50px;
	padding-top:15px
}
.loader {
  position: relative;
  height: 20px;
  width: 20px;
  display: inline-block;
  animation: around 5.4s infinite;
}

@keyframes around {
  0% {
    transform: rotate(0deg)
  }
  100% {
    transform: rotate(360deg)
  }
}

.loader::after, .loader::before {
  content: "";
  background: white;
  position: absolute;
  display: inline-block;
  width: 100%;
  height: 100%;
  border-width: 2px;
  border-color: #333 #333 transparent transparent;
  border-style: solid;
  border-radius: 20px;
  box-sizing: border-box;
  top: 0;
  left: 0;
  animation: around 0.7s ease-in-out infinite;
}

.loader::after {
  animation: around 0.7s ease-in-out 0.1s infinite;
  background: transparent;
}</style>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content" style="position:relative">
	
	<div class="inputcontainer">
    <input type="text" id="filter-input" placeholder="Stichwort">
    <div class="icon-container hidden">
      <i class="loader"></i>
    </div>
  </div>


<div id="results"></div>

</div>
</article><!-- #post-<?php the_ID(); ?> -->



<script>

	let result = [];

	/* one fetch and filtering on json data */
	const fetchOnceAndFilter = async (value) => {
		let posts = `https://foobar:8890/wp-json/wp/v2/custom`;

		if(!result.posts) {
			let fetched = await fetch(posts);
			let json = await fetched.json();
		
			result['posts'] = json;
		}
		
		let returnable_content = result.posts.filter(element => element.title.rendered.toLowerCase().search(value.toLowerCase()) > -1);
		let returnable = '';

		returnable_content.forEach(element => {
				returnable += '<a>' + element.title.rendered + '</a><hr>';
			})
				
		return returnable	
	}

	/* fetching on keyup event */
	const fetchAll = async (value) => {
		let filtered_posts = `https://foobar:8890/wp-json/wp/v2/custom?s=${value}`;

		/*

		$post_object = (object) [
                'id' => $post->ID,
                'title' => (object) ['rendered' => $post->post_title],
                'date' => $post->post_date,
                'slug' => $post->post_name,
                'link' => $link,
                'featured_img_url' => $featured_image,
                'image' => get_the_post_thumbnail_url($post->ID),
                'excerpt' => (object) ['rendered' => get_the_excerpt()],
                'type' => get_post_type( $post->ID)
            ];

		*/
		let returnable = '';

		try{
			let fetched = await fetch(filtered_posts);
			let json = await fetched.json();
		
			result['filtered_content'] = json;
		
		
			result.filtered_content.forEach(element => {
				returnable += '<a>' + element.title.rendered + '</a><hr>';
			})

		} catch {
			returnable = '<span class=alert alert-danger>Keine Ergebnisse</span>';
		}
		
		return returnable;
	
		
	}

	const handleSpinner = (action) => {
		let spinner = document.querySelector('.icon-container');
		action === 'on' ? spinner.classList.remove('hidden') : spinner.classList.add('hidden')
	}

	let input = document.querySelector('#filter-input');

	/*input.addEventListener('keyup',e => {

		let searchvalue = e.target.value;
		let resultcontainer = document.querySelector('#results');
		
		let len = searchvalue.length;

		console.log('Laenge', searchvalue.length)

		if(len % 2 === 0 && len >= 2) {
			handleSpinner('on')
			fetchAll(e.target.value).then(data => { resultcontainer.innerHTML = data; handleSpinner('off')})
		}
		if(len < 2) {
			resultcontainer.innerHTML = '';
			handleSpinner('off')
		}
		
	}); */

	input.addEventListener('keyup',e => {
		
		let searchvalue = e.target.value;
		let resultcontainer = document.querySelector('#results');

		let len = searchvalue.length;

		console.log('Laenge', searchvalue.length)
		
		if(len % 2 === 0 && len >= 2) {
			handleSpinner('on')
			fetchOnceAndFilter(e.target.value).then(data => { resultcontainer.innerHTML = data;handleSpinner('off') })
		}
		if(len < 2) {
			resultcontainer.innerHTML = '';
			handleSpinner('off')
		}

	});






</script>
