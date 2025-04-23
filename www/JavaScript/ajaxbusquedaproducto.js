
document.addEventListener("DOMContentLoaded", function () {
    const botones = document.querySelectorAll(".btn-info-producto");

    botones.forEach(boton => {
        boton.addEventListener("click", function () {
            const codigo = this.getAttribute("data-codigo");

            fetch("../php/guardarProducto.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `cod_producto=${encodeURIComponent(codigo)}`
            })
                .then(response => {
                    if (response.ok) {
                        window.location.href = "../php/informacionProducto.php";
                    } else {
                        alert("Hubo un error al seleccionar el producto.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error al conectar con el servidor.");
                });
        });
    });
});