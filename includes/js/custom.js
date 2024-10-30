jQuery(document).ready(function() {
  jQuery('.close').click(function(){
    jQuery('.msginfo').css('display','none');
  });
});


jQuery(document).ready(function() {

            jQuery("input[name='ePin_activate']").change(function() {
                var value = jQuery(this).val();
                if (value == '1')
                    jQuery("#sole_id").show();
                else if (value == '0')
                    jQuery("#sole_id").hide();
            });
            jQuery("#rp1").click(function() {
                var value = jQuery('#rp1').val();
                if (value == 'yes')
                    jQuery("#frequency").removeAttr("disabled");
            });
            jQuery("#rp2").click(function() {
                var value = jQuery('#rp2').val();
                if (value == 'no')
                    jQuery("#frequency").attr("disabled", "disabled");
            });
        });

        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57 || charCode == 47))
                return false;

            return true;
        }