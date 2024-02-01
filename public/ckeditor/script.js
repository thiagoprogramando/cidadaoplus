ClassicEditor
	.create(document.querySelector('.editor'), {
	} )
	.then( editor => {
		window.editor = editor;
	} )
	.catch( handleSampleError );

	function handleSampleError(error) {
        console.error('Error during ClassicEditor initialization:', error);
    }
