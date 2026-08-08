<?php
$appros = $viewData['appros'] ?? [];
$articlesEnRupture = $viewData['articlesEnRupture'] ?? [];
$approsValide = $viewData['approsValide'] ?? [];
$approsAValide = $viewData['approsAValide'] ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SupplyPro | Console d'Approvisionnement & Logistique</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php asset("css/appro.css") ?>">
</head>
<body>
    <div class="app-container">
        <!-- ========================================================= NAVBAR ========================================================== -->
        <div class="navbar">
            <div class="nav-logo">
                <span>📦</span>
                SupplyPro Pro
                <span style=" font-size:12px; font-weight:500; color:var(--text-muted); margin-left:10px; border-left:1px solid var(--border-color); padding-left:10px; ">
                    Console d'Approvisionnement
                </span>
            </div>
            <div class="system-status">
                <div class="status-pill online">
                    Dépôt Principal Active
                </div>
                <div class="status-pill">
                    Bordereaux Automatisés
                </div>
                <a href="debt_dashboard.html" class="back-link" style=" margin-left:10px; color:var(--text-muted); text-decoration:none; font-weight:600; font-size:13px; ">
                    Dashboard →
                </a>
            </div>
        </div>
        <!-- ========================================================= MAIN LAYOUT ========================================================== -->
        <div class="main-layout">
            <!-- ===================================================== LEFT ====================================================== -->
            <div>
                <!-- ================= SAISIE APPROVISIONNEMENT ================= -->
                <div class="panel-card" style=" padding:24px; border:1px solid rgba(14,165,233,0.2); background:linear-gradient( 180deg, rgba(17,24,43,0.5) 0%, rgba(10,15,30,0.3) 100% ); ">
                    <div class="panel-title" style=" border-left-color:var(--accent); display:flex; justify-content:space-between; align-items:center; ">
                        <span>🚚 Saisie d'Approvisionnement</span>
                        <span style=" font-size:11px; font-weight:600; color:var(--text-muted); background:rgba(255,255,255,0.03); padding:4px 8px; border-radius:6px; ">
                            Nouveau Lot
                        </span>
                    </div>
                    <form id="supply-mock-form" method="POST" action="">
                        <input type="hidden" name="action" value="enregistrer">
                        <input type="hidden" name="fournisseur_id" id="fournisseur-id">
                        <input type="hidden" name="refBL" id="refbl-hidden">
                        <input type="hidden" name="articles" id="articles-hidden">
                        <!-- FOURNISSEUR -->
                        <div class="form-group">
                            <label for="supplier-select">
                                Fournisseur Partenaire
                            </label>
                            <select id="supplier-select" class="form-control" style="width:100%;">
                                <option value="3">
                                    Comptoir Céréalier Sénégalais (CCS)
                                </option>
                                <option value="4">
                                    Grossiste Alimentaire Diop & Frères
                                </option>
                                <option value="5">
                                    SODIDA Distributeurs Réunis
                                </option>
                            </select>
                        </div>
                        <!-- ARTICLES -->
                        <div style=" border-top:1px dashed var(--border-color); padding-top:16px; margin-top:16px; margin-bottom:16px; ">
                            <label style=" font-size:12px; font-weight:700; color:var(--accent); display:block; margin-bottom:8px; text-transform:uppercase; ">
                                Sélection des Articles & Coûts d'Achat
                            </label>
                            <div style=" display:grid; grid-template-columns:2fr 1fr auto; gap:12px; align-items:flex-end; margin-bottom:16px; ">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="pos-item-select">
                                        Article
                                    </label>
                                    <select id="pos-item-select" class="form-control">
                                        <option value="1" data-name="Sac de riz 50kg" data-price="21000">
                                            Sac de riz 50kg
                                            (Coût d'achat : 21 000 F)
                                        </option>
                                        <option value="2" data-name="Bidon d'huile 5L" data-price="6500">
                                            Bidon d'huile 5L
                                            (Coût d'achat : 6 500 F)
                                        </option>
                                        <option value="3" data-name="Carton de savon" data-price="9500">
                                            Carton de savon
                                            (Coût d'achat : 9 500 F)
                                        </option>
                                        <option value="4" data-name="Paquet de sucre 1kg" data-price="1200">
                                            Paquet de sucre 1kg
                                            (Coût d'achat : 1 200 F)
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="pos-qty">
                                        Quantité Lot
                                    </label>
                                    <input type="number" id="pos-qty" class="form-control" value="10" min="1">
                                </div>
                                <button type="button" class="btn-submit" onclick="addToCart(event)" style=" height:46px; width:46px; font-size:18px; display:flex; justify-content:center; align-items:center; font-weight:bold; border-radius:10px; ">
                                    +
                                </button>
                            </div>
                            <!-- PANIER -->
                            <table class="debt-table" style="font-size:12px;">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Qté Livrée</th>
                                        <th>Coût Achat Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-rows">
                                    <tr id="empty-cart-row">
                                        <td colspan="4" style=" text-align:center; color:var(--text-muted); padding:16px 0; ">
                                            Aucun article dans ce lot.
                                            Ajoutez des lignes.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- TOTAL -->
                        <div style=" background:linear-gradient( 135deg, rgba(14,165,233,0.08), rgba(30,41,59,0.4) ); border:1px solid rgba(14,165,233,0.15); border-radius:16px; padding:18px; text-align:center; margin-bottom:20px; ">
                            <span style=" font-size:11px; color:var(--text-muted); text-transform:uppercase; font-weight:700; display:block; margin-bottom:4px; ">
                                Valorisation Globale du Lot d'Entrée
                            </span>
                            <div style=" font-size:28px; font-weight:900; color:#38bdf8; ">
                                <span id="montant_total_display_text">
                                    0
                                </span>
                                <span style="font-size:16px;">
                                    FCFA
                                </span>
                            </div>
                        </div>
                        <!-- REFERENCE -->
                        <div class="form-group">
                            <label for="reference-bordereau">
                                Référence du Bordereau de Livraison
                            </label>
                            <input type="text" id="reference-bordereau" class="form-control" name="refBL" placeholder="Ex: BL-CCS-2026-98">
                        </div>
                        <button type="submit" class="btn-submit btn-success" style=" padding:16px 24px; font-weight:800; font-size:14px; ">
                            Enregistrer
                        </button>
                    </form>
                </div>
                <!-- ================= FOURNISSEUR ================= -->
                <div class="panel-card" style="margin-top:32px;">
                    <div class="panel-title">
                        Enregistrer un Fournisseur
                    </div>
                    <form onsubmit=" event.preventDefault(); alert('Fournisseur simulé enregistré avec succès !'); ">
                        <div class="form-group">
                            <label>
                                Nom de l'Entreprise
                            </label>
                            <input type="text" class="form-control" placeholder="Ex: Comptoir Céréalier Sénégalais">
                        </div>
                        <div class="form-group">
                            <label>
                                Téléphone de Contact
                            </label>
                            <input type="text" class="form-control" placeholder="Ex: 338245678">
                        </div>
                        <div class="form-group">
                            <label>
                                Adresse / Ville
                            </label>
                            <input type="text" class="form-control" placeholder="Ex: Port de Dakar">
                        </div>
                        <button type="submit" class="btn-submit">
                            Créer le Fournisseur
                        </button>
                    </form>
                </div>
            </div>
            <!-- ===================================================== RIGHT ====================================================== -->
            <div>
                <!-- ================= BORDEREAUX ================= -->
                <div class="panel-card" style="margin-bottom:32px;">
                    <div class="panel-title"> Bordereaux de Livraison (Réceptions)</div>
                    <!-- FILTRES -->
                    <div class="filter-ribbon">
                        <div class="search-bar">
                            <input type="text" id="search-input" class="search-input" onkeyup="filterSlips()" placeholder="Rechercher par Fournisseur ou BL...">
                        </div>
                        <div class="filter-chips">
                            <span class="chip active" onclick="setFilter('tous', this)"> Tout</span>
                            <span class="chip" onclick="setFilter('encours', this)"> En cours</span>
                            <span class="chip" onclick="setFilter('receptionne', this)"> Réceptionnés</span>
                        </div>
                    </div>
                    <!-- LISTE -->
                    <div id="slips-container">
                        <?php foreach ($approsAValide as $appro): ?>
                            <div class="panel-card slip-card" data-supplier="<?= htmlspecialchars($appro['fournisseur_id'] ?? '') ?>" data-ref="<?= htmlspecialchars($appro['refBL'] ?? '') ?>" data-status="encours" style=" padding:20px; border-radius:16px; margin-bottom:16px; background:rgba(255,255,255,0.01); ">
                                <div style=" display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; ">
                                    <div>
                                        <span style=" font-size:11px; color:var(--text-muted); font-weight:700; ">
                                            Réf:#<?= htmlspecialchars($appro['refBL'] ?? '') ?>
                                        </span>
                                        <div style=" font-size:16px; font-weight:700; margin-top:5px; ">
                                            Fournisseur #<?= htmlspecialchars($appro['fournisseur_id'] ?? '') ?>
                                        </div>
                                    </div>
                                    <span class="badge non-payee" id="status-session-<?= $appro['id'] ?>">
                                        En Cours
                                    </span>
                                </div>
                                <div style=" display:flex; justify-content:space-between; align-items:center; ">
                                    <div style=" font-size:18px; font-weight:800; color:var(--accent); ">
                                        <?php
                                        $totalAppro = 0;
                                        foreach ($appro['articles'] as $article) {
                                            $totalAppro += ($article['quantiteAppro'] ?? 0) * ($article['prixAppro'] ?? 0);
                                        }
                                        ?>
                                        <?= htmlspecialchars($totalAppro) ?> FCFA
                                    </div>
                                    <div style=" display:flex; gap:8px; ">
                                        <button type="button" class="btn-quick-action" onclick="toggleDetails('details-session-<?= $appro['id'] ?>')">
                                            Voir articles
                                        </button>
                                    </div>
                                </div>
                                <!-- ================= DRAWER ================= -->
                                <div class="details-drawer" id="details-session-<?= $appro['id'] ?>" style="display:none; margin-top:16px;">
                                    <div style=" font-weight:700; font-size:12px; color:var(--accent); margin-bottom:12px; ">
                                        Saisie des Quantités Reçues et Coûts Réels
                                    </div>
                                    <form action="" method="POST">
                                        <input type="hidden" name="action" value="receptionner">
                                        <input type="hidden" name="appro_id" value="<?= $appro['id'] ?>">
                                        <?php foreach ($appro['articles'] as $index => $article): ?>
                                            <div style=" display:grid; grid-template-columns:2fr 1fr 1.2fr; gap:12px; align-items:center; padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.03); ">
                                                <span style=" font-weight:700; font-size:12px; ">
                                                    <?= htmlspecialchars($article['libelle'] ?? '') ?>
                                                </span>
                                                <input type="hidden" name="article[<?= $index ?>][article_id]" value="<?= $article['article_id'] ?>">
                                                <input type="hidden" name="article[<?= $index ?>][quantiteAppro]" value="<?= $article['quantiteAppro'] ?>">
                                                <div>
                                                    <label style=" font-size:9px; color:var(--text-muted); display:block; margin-bottom:2px; "> Qté Reçue </label>
                                                    <input type="number" class="form-control" name="article[<?= $index ?>][quantiteRecu]" value="<?= $article['quantiteRecu'] ?? 0 ?>" max="<?= $article['quantiteAppro'] ?>" min="0">
                                                </div>
                                                <div>
                                                    <label style=" font-size:9px; color:var(--text-muted); display:block; margin-bottom:2px; "> Coût Achat (F) </label>
                                                    <input type="number" class="form-control" name="article[<?= $index ?>][prixAppro]" value="<?= $article['prixAppro'] ?>" min="0">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <button type="submit" class="btn-submit btn-success" style=" margin-top:14px; padding:10px 16px; font-size:11px; width:auto; ">
                                            Confirmer et réceptionner
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php foreach ($approsValide as $appro): ?>
                            <div class="panel-card slip-card" data-supplier="<?= htmlspecialchars($appro['nomentrprise'] ?? '') ?>" data-ref="<?= htmlspecialchars($appro['refbl'] ?? '') ?>" data-status="receptionne" style=" padding:20px; border-radius:16px; margin-bottom:16px; background:rgba(255,255,255,0.01); ">
                                <div style=" display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; ">
                                    <div>
                                        <span style=" font-size:11px; color:var(--text-muted); font-weight:700; ">
                                            Réf:#<?= htmlspecialchars($appro['refbl'] ?? '') ?>|<?= htmlspecialchars($appro['dateappro'] ?? '') ?>
                                        </span>
                                        <div style=" font-size:16px; font-weight:700; margin-top:5px; ">
                                            <?= htmlspecialchars($appro['nomentrprise'] ?? '') ?>
                                        </div>
                                    </div>
                                    <span class="badge payee" id="status-<?= $appro['id'] ?>">
                                        <?= htmlspecialchars($appro['nometat'] ?? 'Réceptionné') ?>
                                    </span>
                                </div>
                                <div style=" display:flex; justify-content:space-between; align-items:center; ">
                                    <div style=" font-size:18px; font-weight:800; color:var(--accent); ">
                                        <?= htmlspecialchars($appro['valeurfacture'] ?? 0) ?> FCFA
                                    </div>
                                    <div style=" display:flex; gap:8px; ">
                                        <button type="button" class="btn-quick-action" onclick="toggleDetails('details-<?= $appro['id'] ?>')">
                                            Voir articles
                                        </button>
                                    </div>
                                </div>
                                <!-- ================= DRAWER ================= -->
                                <div class="details-drawer" id="details-<?= $appro['id'] ?>" style="display:none; margin-top:16px;">
                                    <div style=" font-weight:700; font-size:12px; color:var(--accent); margin-bottom:8px; ">
                                        Lignes d'articles reçus :
                                    </div>
                                    <?php foreach ($appro['articles'] ?? [] as $article): ?>
                                        <div style=" display:flex; justify-content:space-between; font-size:12px; color:var(--text-muted); margin-bottom:6px; ">
                                            <span><?= htmlspecialchars($article['libelle'] ?? '') ?></span>
                                            <span>Réceptionné</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- ================= STOCK ================= -->
                <div class="panel-card">
                    <div class="panel-title" style="border-left-color:var(--danger);"> ⚠️ Niveaux de Stocks & Approvisionnement direct </div>
                    <p style=" font-size:13px; color:var(--text-muted); margin-bottom:16px; "> Générez instantanément des bons de commande pour vos fournisseurs. </p>
                    <div style=" display:flex; flex-direction:column; gap:14px; ">
                        <?php foreach ($articlesEnRupture as $article): ?>
                            <div>
                                <div style=" display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid rgba(255,255,255,0.03); padding-bottom:8px; ">
                                    <div>
                                        <div style=" font-weight:700; font-size:13px; "> <?= htmlspecialchars($article['libelle']) ?> </div>
                                        <span style=" font-size:11px; color:var(--<?= htmlspecialchars($article['couleur']) ?>); font-weight:700; "> <?= htmlspecialchars($article['qtestock']) ?> </span>
                                    </div>
                                    <button class="btn-quick-action" onclick=" toggleOrderDraft( 'draft-<?= $article['id'] ?>', '<?= htmlspecialchars($article['libelle'], ENT_QUOTES) ?>', '<?= htmlspecialchars($article['nomentrprise'], ENT_QUOTES) ?>', 50 ) "> Commander </button>
                                </div>
                                <!-- BON DE COMMANDE -->
                                <div class="order-draft-panel" id="draft-<?= $article['id'] ?>" style="display:none; margin-top:10px;">
                                    <div style=" font-weight:700; margin-bottom:6px; color:var(--accent); "> Demande d'Approvisionnement Automatique </div>
                                    <textarea class="draft-textarea" id="text-draft-<?= $article['id'] ?>"></textarea>
                                    <button type="button" class="btn-quick-action" style=" font-size:10px; width:100%; border-color:var(--success); color:var(--success); margin-top:6px; " onclick="copyDraft('text-draft-<?= $article['id'] ?>')"> Copier le bon de commande</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- ================= GRAND LIVRE ================= -->
                <div class="panel-card" style="margin-top:32px;">
                    <div class="panel-title success-border">
                        Grand Livre de Rapprochement des Entrées
                    </div>
                    <table class="debt-table" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>Réf BL</th>
                                <th>Fournisseur</th>
                                <th>Valeur Facturée</th>
                                <th>Valeur Réceptionnée</th>
                                <th>Diagnostic</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appros as $appro): ?>
                                <tr>
                                    <td style="font-weight:700;"> #<?= htmlspecialchars($appro['refbl']) ?> </td>
                                    <td> <?= htmlspecialchars($appro['nomentrprise']) ?> </td>
                                    <td> <?= htmlspecialchars($appro['valeurfacture']) ?> FCFA </td>
                                    <td> <?= htmlspecialchars($appro['valeurreceptionne']) ?> FCFA </td>
                                    <td style=" color:var(--<?= htmlspecialchars($appro['couleur']) ?>); font-weight:700; "> <?= htmlspecialchars($appro['typee']) ?> </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php asset("js/appro.js") ?>"></script>
</body>
</html>