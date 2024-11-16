const observador = new IntersectionObserver((entradas) => {
    entradas.forEach(entrada => {
        if(entrada.isIntersecting){
            entrada.target.classList.add("aparecendo")
        } else {
            entrada.target.classList.remove("aparecendo")
        }
    })
})

const elementos = document.querySelectorAll('.escondido')

elementos.forEach(element => {
    observador.observe(element)
})

