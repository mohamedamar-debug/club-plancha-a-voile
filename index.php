<?php
/**
 * index.php
 * Page d'accueil — Point d'entrée principal
 */
declare(strict_types=1);

require_once __DIR__ . '/config/security.php';

$page_title = 'Accueil';
$current_page = 'index';

require __DIR__ . '/views/layout/header.php';
?>

  <section class="hero" aria-label="Présentation du club">
    <div class="hero-content">
      <div class="hero-badge">⚓ Fondé en 1987 · Archipel de Kerkennah</div>
      <h1>Naviguez avec l'Âme<br/>de la Méditerranée</h1>
      <p>
        Plongez dans la tradition millénaire de la voile kerkennienne.
        Le Club des Felouques vous invite à redécouvrir la mer à travers
        les embarcations en bois qui ont façonné l'identité de notre archipel.
      </p>
      <div class="hero-btns">
        <a href="contact.php" class="btn btn-primary">⚓ Rejoindre le club</a>
        <a href="histoire.html" class="btn btn-secondary">Découvrir l'histoire →</a>
      </div>
    </div>
  </section>

  <main>

    <div class="info-band" role="region" aria-label="Chiffres clés du club">
      <div class="stat">
        <span class="stat-num">37</span>
        <span class="stat-label">Années d'existence</span>
      </div>
      <div class="stat">
        <span class="stat-num">120+</span>
        <span class="stat-label">Membres actifs</span>
      </div>
      <div class="stat">
        <span class="stat-num">18</span>
        <span class="stat-label">Felouques en flotte</span>
      </div>
      <div class="stat">
        <span class="stat-num">45+</span>
        <span class="stat-label">Courses annuelles</span>
      </div>
    </div>

    <section aria-labelledby="titre-presentation">
      <div class="section-title">
        <span class="icon">🌊</span>
        <h2 id="titre-presentation">Le Club en quelques mots</h2>
      </div>

      <div class="highlight-box">
        <h3>Notre Mission</h3>
        <p>
          Le <strong>Club des Felouques de Kerkennah</strong> est une association dédiée à la
          préservation et à la promotion de la navigation traditionnelle dans l'archipel de Kerkennah.
        </p>
        <p>
          Nous perpétuons l'art ancestral de la construction et du pilotage des <em>felouques</em> —
          ces élégantes embarcations en bois à voile latine qui sillonnent la Méditerranée depuis
          des siècles. Un patrimoine vivant, entre tradition et passion moderne.
        </p>
        <a href="histoire.html" class="btn btn-mer mt-1">Lire notre histoire complète →</a>
      </div>
    </section>

     <!-- Nos activités -->
    <section aria-labelledby="titre-activites">
      <div class="section-title">
        <span class="icon">🏆</span>
        <h2 id="titre-activites">Nos Activités</h2>
      </div>

      <div class="cards-grid">
        <article class="card">
          <div class="card-img"><img src="./media/Courses de Felouques.png" alt="Felouque" /></div>
          <div class="card-body">
            <h3>Courses de Felouques</h3>
            <p>
              Des régates régulières entre les îles de Kerkennah, sur des parcours
              qui mettent en valeur la maîtrise des vents méditerranéens.
            </p>
            <a href="calendrier.html" class="btn btn-mer">Voir le calendrier</a>
          </div>
        </article>

        <article class="card">
          <div class="card-img"><img src="./media/Ateliers.png" alt="Atelier de construction" /></div>
          <div class="card-body">
            <h3>Ateliers de Construction</h3>
            <p>
              Apprenez les techniques traditionnelles de construction navale
              aux côtés de nos charpentiers de marine expérimentés.
            </p>
            <a href="contact.php" class="btn btn-mer">S'inscrire</a>
          </div>
        </article>

        <article class="card">
          <div class="card-img"><img src="./media/Formation.png" alt="Formation à la Navigation" /></div>
          <div class="card-body">
            <h3>Formation à la Navigation</h3>
            <p>
              Des cours pour tous les niveaux : initiation, perfectionnement,
              et certification de skipper de felouque traditionnelle.
            </p>
            <a href="contact.html" class="btn btn-mer">En savoir plus</a>
          </div>
        </article>

        <article class="card">
          <div class="card-img"><img src="./media/festival.png" alt="Événements Culturels" /></div>
          <div class="card-body">
            <h3>Événements Culturels</h3>
            <p>
              Festivals maritimes, expositions photo et rencontres avec les pêcheurs
              pour célébrer la culture maritime de Kerkennah.
            </p>
            <a href="galerie.html" class="btn btn-mer">Voir la galerie</a>
          </div>
        </article>
      </div>
    </section>

    <!-- Kerkennah : le lieu -->
    <section aria-labelledby="titre-archipel">
      <div class="section-title">
        <span class="icon">🗺️</span>
        <h2 id="titre-archipel">L'Archipel de Kerkennah</h2>
      </div>

      <div class="highlight-box">
        <h3>Un Archipel Unique en Méditerranée</h3>
        <p>
          Composé de deux îles principales, <strong>Gharbi</strong> (la Grande) et
          <strong>Chergui</strong> (la Petite), l'archipel de Kerkennah est réputé pour
          ses eaux peu profondes, ses herbiers de posidonie et ses magnifiques couchers de soleil
          sur le golfe de Gabès.
        </p>
        <p>
          C'est dans cet environnement exceptionnel que la tradition de la pêche à la
          <em>charfia</em> (palissade de roseaux) et de la navigation à la voile s'est
          développée au fil des siècles, faisant de Kerkennah un lieu unique au monde.
        </p>
        <p>
          En savoir plus sur l'archipel :
          <a href="https://fr.wikipedia.org/wiki/Kerkennah" target="_blank" rel="noopener" style="color: var(--bleu-mer); text-decoration: underline;">
            Wikipedia — Kerkennah ↗
          </a>
        </p>
      </div>
    </section>

    <!-- Actualités rapides -->
    <section aria-labelledby="titre-actu">
      <div class="section-title">
        <span class="icon">📰</span>
        <h2 id="titre-actu">Dernières Nouvelles</h2>
      </div>

      <div class="cards-grid">
        <article class="card">
          <div class="card-body">
            <h3>Régate de Printemps 2026</h3>
            <p style="font-size:0.82rem; color:var(--sable); margin-bottom:0.5rem;">15 Avril 2026</p>
            <p>La régate annuelle de printemps rassemblera 24 felouques sur un parcours de 18 milles nautiques.</p>
            <a href="calendrier.php" class="btn btn-mer">Détails →</a>
          </div>
        </article>

        <article class="card">
          <div class="card-body">
            <h3>Nouveau Chantier Naval</h3>
            <p style="font-size:0.82rem; color:var(--sable); margin-bottom:0.5rem;">3 Mars 2026</p>
            <p>Le club inaugure son nouveau chantier de construction et de restauration de felouques traditionnelles.</p>
            <a href="galerie.html" class="btn btn-mer">Voir les photos →</a>
          </div>
        </article>

        <article class="card">
          <div class="card-body">
            <h3>Inscriptions Ouvertes</h3>
            <p style="font-size:0.82rem; color:var(--sable); margin-bottom:0.5rem;">29 Janvier 2026</p>
            <p>Les inscriptions pour la saison 2026 sont ouvertes. Rejoignez notre communauté de passionnés !</p>
            <a href="contact.php" class="btn btn-primary">S'inscrire →</a>
          </div>
        </article>
      </div>
    </section>

  </main>

<?php require __DIR__ . '/views/layout/footer.php'; ?>