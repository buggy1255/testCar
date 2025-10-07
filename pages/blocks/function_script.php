<script type="text/javascript">
    // show(123);

	function select_course( code ){
  		$.ajax({
		   type: "POST",
		   url: "<?=$defaultLinks['ajax']?>",
		   data: "task=selectСourse&code="+code,
		   success: function(msg){
				location.reload();
		   }
		 });
  	}
  	
  	/* подгрузка даты для второго инпут */
	function sel_date( classInpt ) {
    	var s1date = $.trim($('#overPoint .dateClass').val());
    	if( s1date == '' ){return false;}
    	var fd = new FormData;
	    fd.append('task', 'selDate' );
	    fd.append('s1date', s1date );
	    fd.append('classInpt', classInpt );
	    
		$.ajax({
            url: '<?=$defaultLinks['ajax']?>',
            data: fd,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function ( msg ) {
            	/*dd/mm/yy*/
    			$('#returnPoint .timeClass').attr({'disabled': false});
                $('#returnPoint .dateClassBlock').html( msg );
                
                setTimeout(function(){
                	$('.datetimepicker1').attr({'placeholder' : 'dd/mm/yy' });
                	$('.timepicker').attr({'placeholder' : '00:00' });
                	
                },600);
                
		
		      }
		 });
    }
    
</script>