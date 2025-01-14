
/**
 * Gestion du boutton "Sélectionner fichier" dans les modal "Rendu" et "Support"
 */
const selectFileButtonList = document.querySelectorAll('[class*="selectFileButton-"]');
selectFileButtonList.forEach(element =>{
  element
})

/**
* Gestion du modal (pop up) "Dépot Rendu"
**/

const modalDepotRendu = document.getElementById('modalDepotRendu')
const dropRenduFileCancelButton = document.getElementById('depotCancelButtonRendu')
const listRenduButtons = document.querySelectorAll('[class*="rendudrop-"]');
const idSaeDepotRendu = document.getElementById('idSaeDepotRendu');

document.getElementById('selectFileButtonRendu').addEventListener('click', function() {
  document.getElementById('fileInputRendu').click();
});

dropRenduFileCancelButton.addEventListener('click', function (){
  modalDepotRendu.classList.remove('d-block');
});

listRenduButtons.forEach(element => {
  const matches = element.className.match(/rendudrop-(\d+)/);
  if(matches){
    const number = matches[1]
    element.addEventListener('click', function (){
      idSaeDepotRendu.value = number;
      modalDepotRendu.classList.add('d-block');
    });
  }
});

/**
* Gestion du modal (pop up) "Dépot Support"
**/

const modalDepotSupport = document.getElementById('modalDepotSupport')
const dropSupportFileCancelButton = document.getElementById('depotCancelButtonSupport')
const listSupportButtons = document.querySelectorAll('[class*="rendusoutenance-"]');
const idSaeDepotSupport = document.getElementById('idSaeDepotSupport');

document.getElementById('selectFileButtonSupport').addEventListener('click', function() {
  document.getElementById('fileInputSupport').click();
});

dropSupportFileCancelButton.addEventListener('click', function (){
  modalDepotSupport.classList.remove('d-block');
});

listSupportButtons.forEach(element => {
    matches = element.className.match(/rendusoutenance-(\d+)/);
    element.addEventListener('click', function (){
      console.log("support button clicked");
      idSaeDepotSupport.value = matches[1];
      modalDepotSupport.classList.add('d-block');
    });
  
});

/**
 * Gestion du modal (pop-up) "Suprimmer Rendu"
 */

const modalSupressionRendu = document.getElementById('modalSupressionDepotRendu');
const modalSupressionRenduCancelButton = document.getElementById('modalSupressionDepotRendu');
const modalValiderRendu = document.getElementById('modalValiderDepotRendu');

const listSupressionRenduButtons = document.querySelectorAll('[class*="supressRenduButton"]');

listSupressionRenduButtons.forEach(element => {
  element.addEventListener('click', function(){
    matches = element.className.match(/supressRenduButton-(\d+)/);
    document.getElementById('idDepotSupressionRendu').value = matches[1];
    modalSupressionRendu.classList.add('d-block');
  })
})
modalSupressionRenduCancelButton.addEventListener('click', function(){
  modalSupressionRendu.classList.remove('d-block');
})

/**
 * Gestion du modal (pop-up) "Suprimmer Support"
 */

const modalSupressionSupport = document.getElementById('modalSupressionDepotSupport');
const modalSupressionSupportCancelButton = document.getElementById('modalSupressionDepotSupport');
const modalValiderSupport = document.getElementById('modalValiderDepotSupport');

const listSupressionSupportButtons = document.querySelectorAll('[class*="supressSupportButton-"]');
console.log(listSupressionRenduButtons);
listSupressionSupportButtons.forEach(element => {
  element.addEventListener('click', function(){
    matches = element.className.match(/supressSupportButton-(\d+)/);
    document.getElementById('idDepotSupressionSupport').value = matches[1];
    modalSupressionSupport.classList.add('d-block');
  })
})
modalSupressionSupportCancelButton.addEventListener('click', function(){
  modalSupressionSupport.classList.remove('d-block');
})






        