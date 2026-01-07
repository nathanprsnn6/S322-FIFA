@extends('layouts.app')

@section('content')
    
<div class="faq-page">
    <header class="faq-header">
        <h1>Centre d'Aide FIFA - Support Officiel</h1>
    </header>

    <main class="faq-wrapper">

        <section class="faq-section">
            <h2 class="category-title">Votes et Récompenses "The Best"</h2>

            <div class="faq-card">
                <details>
                    <summary>Qui peut voter pour les trophées "The Best" ?</summary>
                    <div class="faq-answer">
                        <p>Tout visiteur peut accéder au système de vote et consulter les trophées des joueurs. Cependant, il est nécessaire d'être un <strong>utilisateur connecté</strong> pour valider officiellement son vote.</p>
                    </div>
                </details>
            </div>

            <div class="faq-card">
                <details>
                    <summary>Combien de joueurs puis-je sélectionner ?</summary>
                    <div class="faq-answer">
                        <p>Pour chaque catégorie, vous devez voter pour <strong>trois joueurs</strong>. Vous devrez ensuite confirmer votre choix pour que le vote soit enregistré.</p>
                    </div>
                </details>
            </div>
        </section>

        <section class="faq-section">
            <h2 class="category-title">Boutique en ligne (Store)</h2>

            <div class="faq-card">
                <details>
                    <summary>Comment rechercher un produit spécifique ?</summary>
                    <div class="faq-answer">
                        <p>Vous disposez de plusieurs modes de recherche :</p>
                        <ul>
                            <li>Par <strong>nation</strong> (via les drapeaux).</li>
                            <li>Par <strong>catégorie</strong> (Maillots, Vêtements, Accessoires, Objets de collection).</li>
                            <li>Par <strong>compétition</strong>.</li>
                        </ul>
                    </div>
                </details>
            </div>

            <div class="faq-card">
                <details>
                    <summary>Le prix change-t-il selon la taille choisie ?</summary>
                    <div class="faq-answer">
                        <p>Non. Le prix d'un produit est défini selon son <strong>coloris</strong>, mais il ne dépend pas de la taille sélectionnée.</p>
                    </div>
                </details>
            </div>

            <div class="faq-card">
                <details>
                    <summary>Puis-je enregistrer mes coordonnées bancaires ?</summary>
                    <div class="faq-answer">
                        <p>Oui, lors du règlement, le site propose de conserver vos informations pour vos prochains achats. Ces données sont protégées par un algorithme de chiffrement robuste.</p>
                    </div>
                </details>
            </div>
                        <div class="faq-card">
                <details>
                    <summary>Quels sont les modes de paiement acceptés ?</summary>
                    <div class="faq-answer">
                        <p>Nous acceptons les cartes bancaires (Visa, MasterCard, American Express) ainsi que les paiements via <strong>PayPal</strong> et Apple Pay pour plus de rapidité.</p>
                    </div>
                </details>
            </div>

                    <div class="faq-card">
                        <details>
                            <summary>Le paiement est-il sécurisé ?</summary>
                            <div class="faq-answer">
                                <p>Absolument. Toutes les transactions sont chiffrées via le protocole <strong>SSL/TLS</strong>. Vos données bancaires ne sont jamais stockées en clair sur nos serveurs.</p>
                            </div>
                        </details>
                    </div>
        </section>

        <section class="faq-section">
            <h2 class="category-title">Livraisons et Commandes</h2>

            <div class="faq-card">
                <details>
                    <summary>Comment annuler ma commande ou retourner un article ?</summary>
                    <div class="faq-answer">
                        <p>Rendez-vous dans <strong>"Mon compte"</strong>, puis <strong>"Mes commandes"</strong> et cliquez sur <strong>"Voir articles"</strong>. Un bouton <strong>"Annuler ma commande"</strong> est disponible en bas de la page pour les commandes non expédiées.</p>
                        <p>Pour un retour, vous disposez de <strong>14 jours</strong> après réception pour imprimer votre bon de retour.</p>
                    </div>
                </details>
            </div>

            <div class="faq-card">
                <details>
                    <summary>Quels sont les délais de livraison ?</summary>
                    <div class="faq-answer">
                        <p>Les délais varient selon le mode choisi :</p>
                        <ul>
                            <li><strong>Express (France Express)</strong> : livraison garantie sous 3 jours.</li>
                            <li><strong>Standard (Geodis, Trusk, Heppner, Chronopost)</strong> : livraison entre 2 et 5 jours.</li>
                        </ul>
                    </div>
                </details>
            </div>

            <div class="faq-card">
                <details>
                    <summary>Que se passe-t-il si j'émets des "réserves" à la livraison ?</summary>
                    <div class="faq-answer">
                        <p>Si vous indiquez une réserve sur le bon de transport (colis abîmé par exemple), celle-ci est saisie dans notre système. Vous avez <strong>15 jours</strong> pour confirmer une réclamation ; sans nouvelles de votre part passé ce délai, la commande est <strong>automatiquement validée</strong>.</p>
                    </div>
                </details>
            </div>
            <div class="faq-card">
    <details>
        <summary>Où puis-je suivre l'avancement de mon colis ?</summary>
        <div class="faq-answer">
            <p>Dès l'expédition de votre commande, un numéro de suivi vous est envoyé par e-mail. Vous pouvez également cliquer sur <strong>"Suivre mon colis"</strong> dans l'onglet "Mes commandes" de votre profil.</p>
        </div>
    </details>
</div>

<div class="faq-card">
    <details>
        <summary>Que faire s'il manque un article dans mon colis ?</summary>
        <div class="faq-answer">
            <p>Si votre commande arrive incomplète, contactez notre support sous 48h en mentionnant votre numéro de commande et en joignant une photo du bon de livraison présent dans le colis.</p>
        </div>
    </details>
</div>
        </section>
        

        <section class="faq-section">
            <h2 class="category-title">Actualités et Médias</h2>

            <div class="faq-card">
                <details>
                    <summary>Quels types de contenus puis-je consulter ?</summary>
                    <div class="faq-answer">
                        <p>Le site propose plusieurs formats :</p>
                        <ul>
                            <li><strong>Albums</strong> : ensembles de photos liées aux joueurs.</li>
                            <li><strong>Articles</strong> : textes avec photos ou films.</li>
                            <li><strong>Documents</strong> : fichiers PDF téléchargeables (rapports annuels, règlements).</li>
                            <li><strong>Blogs</strong> : articles courts permettant aux utilisateurs connectés de poster des commentaires.</li>
                        </ul>
                    </div>
                </details>
            </div>
        </section>

        <section class="faq-section">
            <h2 class="category-title">Assistance Technique</h2>

            <div class="faq-card">
                <details>
                    <summary>Comment obtenir une aide immédiate ?</summary>
                    <div class="faq-answer">
                        <p>Un outil de communication <strong>"Chatbot"</strong> est disponible en bas de chaque page pour vous guider en cas de problème technique lors de votre navigation ou de vos achats.</p>
                    </div>
                </details>
            </div>
            <div class="faq-card">
    <details>
        <summary>J'ai perdu mon mot de passe, comment faire ?</summary>
        <div class="faq-answer">
            <p>Cliquez sur "Connexion" puis sur <strong>"Mot de passe oublié"</strong>. Un lien de réinitialisation vous sera envoyé instantanément sur votre adresse e-mail de contact.</p>
        </div>
    </details>
</div>

<div class="faq-card">
    <details>
        <summary>Comment supprimer mes données personnelles ?</summary>
        <div class="faq-answer">
            <p>Conformément au RGPD, vous pouvez demander la suppression de votre compte et de vos données depuis vos paramètres de profil ou en envoyant une demande via notre formulaire de contact.</p>
        </div>
    </details>
</div>
        </section>
        <section class="faq-contact-section">
            <div class="contact-card">
                <h3>Vous ne trouvez pas de réponse à votre question ?</h3>
                <p>Notre équipe de support est là pour vous aider avec vos votes, vos commandes ou tout autre problème technique.</p>
                <div class="contact-actions">
                    <a href="mailto:support@fifa-store.com" class="btn-contact primary">Nous contacter par email</a>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection