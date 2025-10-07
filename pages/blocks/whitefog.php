<div id="shadowwhite" style="position: fixed;top: 0px;left:0px;z-index: 99999999999999;background: #fff;width: 100%;height: 100%"></div>
<script>
	var DOMReady = function(a, b, c) {
		b = document, c = 'addEventListener';
		b[c] ? b[c]('DOMContentLoaded', a) : window.attachEvent('onload', a)
	};
	DOMReady(function() {
		setTimeout(function(){
			var shadowwhite = document.getElementById('shadowwhite');
			shadowwhite.parentElement.removeChild(shadowwhite);
		},100);
	});
</script>