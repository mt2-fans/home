// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'
  
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
  
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
  
          form.classList.add('was-validated')
        }, false)
      })
  })()

setTimeout(function() {
  var mensagensErro = document.querySelectorAll('.alert-success');
  mensagensErro.forEach(function(mensagem) {
    mensagem.style.display = 'none';
  });
}, 5000);

var checkBoxTerms = document.querySelector('.form-check-input')
var submitButton = document.querySelector('#submit')

checkBoxTerms.addEventListener('click',()=>{
  if(checkBoxTerms.checked){
    submitButton.disabled = false;
  }else{
    submitButton.disabled = true;
  }
})