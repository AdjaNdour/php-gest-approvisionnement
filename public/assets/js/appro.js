let currentFilter = 'tous';

// OUVRIR / FERMER LES ARTICLES

function toggleDetails(id) {
    const panel = document.getElementById(id);
    if (!panel) return;
    if (
        panel.style.display === "none" ||
        panel.style.display === ""
    ) {
        panel.style.display = "block";
    } else {
        panel.style.display = "none";
    }
}

// BOUTON RÉCEPTIONNER

function receptionSlip(id) {
    const panel = document.getElementById(
        "details-" + id
    );
    if (!panel) return;
    panel.style.display = "block";
    panel.scrollIntoView({
        behavior: "smooth",
        block: "nearest"
    });
}

// CONFIRMER RÉCEPTION
// UNIQUEMENT INTERACTION VISUELLE

function confirmReception(id) {
    const panel = document.getElementById(
        "details-" + id
    );
    const button = document.getElementById(
        "btn-receptive-" + id
    );
    const status = document.getElementById(
        "status-" + id
    );
    if (panel) {
        panel.style.display = "none";
    }
    if (button) {
        button.style.display = "none";
    }
    if (status) {
        status.innerText = "RÉCEPTIONNÉ";
        status.className = "badge payee";
    }
}

// BON DE COMMANDE

function toggleOrderDraft(
    panelId,
    item,
    supplier,
    defaultQty
) {
    const panel = document.getElementById(panelId);
    if (!panel) return;
    if (
        panel.style.display === "none" ||
        panel.style.display === ""
    ) {
        panel.style.display = "block";
        const textarea = document.getElementById(
            "text-" + panelId
        );
        if (textarea) {
            textarea.value =
                `BON DE COMMANDE FOURNISSEUR

À l'attention de : ${supplier}
Objet : Commande de réapprovisionnement pour ${item}
Bonjour,
Nous constatons une rupture ou un stock critique sur le produit ${item}.
Nous sollicitons la livraison d'un lot de ${defaultQty} unités à notre dépôt principal.
Merci de nous faire parvenir votre facture proforma correspondante.

Cordialement,
Service Logistique SupplyPro.`;
        }
    } else {
        panel.style.display = "none";
    }
}

// COPIER

function copyDraft(id) {
    const textarea = document.getElementById(id);
    if (!textarea) return;
    textarea.select();
    document.execCommand("copy");
    alert(
        "Message du bon de commande copié dans le presse-papier !"
    );
}

// PANIER

const cart = [];

function addToCart(event) {
    event.preventDefault();
    const select =
        document.getElementById("pos-item-select");
    const qty =
        parseInt(
            document.getElementById("pos-qty").value
        );
    if (!select || qty <= 0) return;
    const price =
        parseFloat(select.value);
    const name =
        select.options[
            select.selectedIndex
        ].getAttribute("data-name");
    const existing =
        cart.find(
            item => item.name === name
        );
    if (existing) {
        existing.qty += qty;
        existing.total =
            existing.qty * existing.price;
    } else {
        cart.push({
            name: name,
            price: price,
            qty: qty,
            total: qty * price
        });
    }
    renderCart();
}

// AFFICHER PANIER

function renderCart() {
    const body =
        document.getElementById("cart-rows");
    const totalDisplay =
        document.getElementById(
            "montant_total_display_text"
        );
    body.innerHTML = "";
    if (cart.length === 0) {
        body.innerHTML = `
            <tr>
                <td colspan="4"
                    style="
                        text-align:center;
                        color:var(--text-muted);
                        padding:16px 0;
                    "
                >
                    Aucun article dans ce lot.
                    Ajoutez des lignes.
                </td>
            </tr>
        `;
        totalDisplay.innerText = "0";
        return;
    }
    let total = 0;
    cart.forEach(
        (item, index) => {
            total += item.total;
            body.innerHTML += `

                <tr>

                    <td style="font-weight:700;">
                        ${item.name}
                    </td>

                    <td>
                        ${item.qty}
                    </td>

                    <td style="
                        font-weight:800;
                        color:var(--accent);
                    ">
                        ${new Intl.NumberFormat(
                'fr-FR'
            ).format(item.total)}
                        F
                    </td>

                    <td style="text-align:right;">

                        <button
                            type="button"
                            onclick="removeCartItem(${index})"
                            style="
                                background:none;
                                border:none;
                                color:var(--danger);
                                cursor:pointer;
                            "
                        >
                            🗑️
                        </button>

                    </td>

                </tr>

            `;
        }
    );
    totalDisplay.innerText =
        new Intl.NumberFormat(
            'fr-FR'
        ).format(total);
}

// SUPPRIMER ARTICLE PANIER

function removeCartItem(index) {
    cart.splice(index, 1);
    renderCart();
}

// FILTRE

function filterSlips() {
    const query =
        document
            .getElementById("search-input")
            .value
            .toLowerCase();
    const cards =
        document.querySelectorAll(
            "#slips-container .slip-card"
        );
    cards.forEach(card => {
        const supplier =
            (
                card.dataset.supplier || ""
            ).toLowerCase();
        const ref =
            (
                card.dataset.ref || ""
            ).toLowerCase();
        const status =
            card.dataset.status;
        const matchesQuery =
            supplier.includes(query) ||
            ref.includes(query);
        const matchesFilter =
            currentFilter === "tous" ||
            status === currentFilter;
        card.style.display =
            matchesQuery && matchesFilter ?
                "block" :
                "none";
    });
}

// CHANGER FILTRE

function setFilter(
    filterType,
    chip
) {
    document
        .querySelectorAll(".chip")
        .forEach(
            c => c.classList.remove("active")
        );
    chip.classList.add("active");
    currentFilter = filterType;
    filterSlips();
}

// SIMULATION ENREGISTREMENT

function addNewDeliverySlip() {
    alert(
        "Approvisionnement enregistré avec succès !"
    );
}
    