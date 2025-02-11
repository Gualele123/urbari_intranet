const tabs = document.querySelectorAll('.tab_btn');  
const all_content = document.querySelectorAll('.item');  

tabs.forEach((tab, index)=>{
    tab.addEventListener('click',()=>{
        tabs.forEach(tab=>{tab.classList.remove('active')});
        tab.classList.add('active');

        all_content.forEach(content=>{content.classList.remove('active')});
        all_content[index].classList.add('active');
        
    })
})

// --------------------------------------------





//--CAROUSEL----------------------------------------------------------------------
const swiper = new Swiper('.slider-wrapper', {
    loop: true,
    grabCursor: true,
    spaceBetween: 30,
  
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
      dynamicBullets: true
    },
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    //responsive braeakpoints,dependiendo del tamaño muestra la cantidad de tarjetas
    breakpoints: {
        0: {
            slidesPerView: 1
        },
        768: {
            slidesPerView: 2
        },
        1024: {
            slidesPerView: 4
        }
    }
  });