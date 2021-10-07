window.onload = init;

function init(){
  document.querySelector('zg-dialog').shadowRoot.querySelector('.zg-dialog-confirm').addEventListener('click', function() {document.querySelector('[action="reload"]').click()});
  setInterval(attach, 250);
  setInterval(prayGridIsLoaded, 350);
}

function prayGridIsLoaded(){
  zgRef = document.querySelector('zing-grid');
  zgRef.hideColumn('id');

  enter = document.querySelector('zg-dialog').shadowRoot.querySelector('#editorRowFieldLabel[data-key=id]');
  if (enter && enter.style.display !== 'none')
    enter.setAttribute('style', 'display: none;');
}

function attach(){
  document.querySelectorAll('[action=editrecord]:not(.attached)').forEach(function(x) {x.addEventListener('click', x => getButton()); x.classList.add('attached'); } );
}

function getButton(){
  button = document.querySelector('[action=submitrecord]:not(.attached)');
  button.addEventListener('click', function() {document.querySelector('[action="reload"]').click()});
  button.classList.add('attached');
}
