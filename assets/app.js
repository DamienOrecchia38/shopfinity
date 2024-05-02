
document.addEventListener('DOMContentLoaded', function() {
    const buyButtons = document.getElementsByClassName('buyButton');
    Array.from(buyButtons).forEach(button => {
        button.addEventListener('submit', confirmPurchase);
    });

    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 1000,
                "density": {
                    "enable": true,
                    "value_area": 1000
                }
            },
            "color": {
                "value": "#ffd700"
            },
            "shape": {
                "type": "circle",
                "stroke": { 
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                }
            },
            "opacity": {
                "value": 0.8,
                "random": true,
                "anim": {
                    "enable": true,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 3,
                "random": true,
                "anim": {
                    "enable": true,
                    "speed": 10,
                    "size_min": 0.1,
                    "sync": false 
                }
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none", 
                "random": true,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false, 
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "bubble"
                },
                "onclick": {
                    "enable": false,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "bubble": {
                    "distance": 400,
                    "size": 4,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                }
            }
        },
        "retina_detect": true
    });

    const particle = document.getElementsByClassName('particle');
    const particlesDiv = document.getElementById('particles-js');
    particle.addEventListener('mouseover', function(event) {
        particlesDiv.style.top = `${event.clientY - 50}px`;
        particlesDiv.style.left = `${event.clientX - 50}px`;
    });

    const signupButton = document.querySelector('.signup-button');
    if (signupButton) {
        signupButton.addEventListener('click', function(event) {
            
            event.preventDefault();

            swal({
                title: "Confirmez votre inscription",
                text: "Êtes-vous sûr de vouloir vous inscrire ?",
                icon: "info",
                buttons: true,
                dangerMode: true,
            }).then((willConfirm) => {
                if (willConfirm) {
                    swal("Vous êtes inscrit !", {
                        icon: "success",
                    }).then(() => {
                        event.target.form.submit();
                    });
                } else {
                    swal("Inscription annulée.");
                }
            });
        });
    }
});

function confirmPurchase(event) {
    event.preventDefault(); 
    const form = event.target;

    swal({
        title: "Êtes-vous sûr ?",
        text: "Voulez-vous ajouter cet article à votre panier ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willBuy) => {
        if (willBuy) {
            swal("L'article a été ajouté à votre panier !", {
                icon: "success",
            }).then(() => {
                form.submit();
            });
        } else {
            swal("L'achat a été annulé.");
        }
    });
}

function showPopup() {
    swal({
      title: 'Tic..Tac.. Promotions INCROYABLES !',
      text: 'Il ne reste que 9 minutes pour profiter de nos offres limitées !',
      icon: 'info',
      button: 'Trop cool'
    });
  }
  
  if (window.location.pathname === '/home') {
    window.onload = function() {
      showPopup();
    }
  }