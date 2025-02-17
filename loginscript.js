window.onload = function() 
{
    var privilegeInput = document.getElementById('privilege');
    privilegeInput.addEventListener('input', function(e)
    {
        this.value = this.value.replace(/[^0-9]/g, ''); 
    });
    
    var urlParams = new URLSearchParams(window.location.search);
    var privilege = urlParams.get('privilege');
    if (privilege !== null)
    {
        privilegeInput.value = privilege;
        privilegeInput.readOnly = true;
    } 

};