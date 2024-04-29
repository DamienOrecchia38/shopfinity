
document.addEventListener('DOMContentLoaded', function() {
    const buyButtons = document.getElementsByClassName('buyButton');
    Array.from(buyButtons).forEach(button => {
        button.addEventListener('submit', confirmPurchase);
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