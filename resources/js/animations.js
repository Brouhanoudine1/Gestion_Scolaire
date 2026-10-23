import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { TextPlugin } from 'gsap/TextPlugin';

gsap.registerPlugin(ScrollTrigger, TextPlugin);

// 1. Hero : apparition au chargement
gsap.from('.accueil-step-one > *', {
  opacity: 0,
  y: 40,
  duration: 0.8,
  stagger: 0.15,
  ease: 'power2.out',
});

gsap.from('.accueil-step-two img', {
  opacity: 0,
  scale: 1.1,
  duration: 1.2,
  ease: 'power2.out',
});

// 2. Barres décoratives qui se déploient de gauche à droite
gsap.utils.toArray('.barre, .bar, .experience-bar, .visite-bar').forEach((bar) => {
  gsap.from(bar, {
    scaleX: 0,
    transformOrigin: 'left center',
    duration: 0.8,
    scrollTrigger: { trigger: bar, start: 'top 90%', once: true },
  });
});

// 3. Compteurs des statistiques
gsap.utils.toArray('.statistiques h3').forEach((h3) => {
  const valeurFinale = parseInt(h3.textContent.replace(/\D/g, ''), 10);
  const compteur = { valeur: 0 };

  h3.textContent = '+0';

  gsap.to(compteur, {
    valeur: valeurFinale,
    duration: 2,
    ease: 'power1.out',
    scrollTrigger: { trigger: h3, start: 'top 85%', once: true },
    onUpdate: () => {
      h3.textContent = '+' + Math.round(compteur.valeur);
    },
  });
});

// 4. Cartes de la section « Notre différence »
gsap.from('.container-card > div', {
  opacity: 0,
  y: 60,
  duration: 0.9,
  stagger: 0.2,
  ease: 'power2.out',
  scrollTrigger: { trigger: '.container-card', start: 'top 80%', once: true },
});

// 5. Titres et textes des sections
gsap.utils.toArray(
  '.container-title, .container-texte, .experience-title, .experience-description, .visite-colonne, .visite-link'
).forEach((el) => {
  gsap.from(el, {
    opacity: 0,
    y: 30,
    duration: 0.9,
    ease: 'power2.out',
    scrollTrigger: { trigger: el, start: 'top 85%', once: true },
  });
});

// 6. Image de la section expérience et sa citation
gsap.from('.experience .image img', {
  opacity: 0,
  scale: 1.15,
  duration: 1.2,
  ease: 'power2.out',
  scrollTrigger: { trigger: '.experience', start: 'top 75%', once: true },
});

gsap.from('.image-texte', {
  opacity: 0,
  y: 20,
  duration: 0.8,
  delay: 0.4,
  scrollTrigger: { trigger: '.experience', start: 'top 75%', once: true },
});

// 7. Points forts, un par un
gsap.from('.quality-one', {
  opacity: 0,
  x: -40,
  duration: 0.7,
  stagger: 0.2,
  ease: 'power2.out',
  scrollTrigger: { trigger: '.quality', start: 'top 80%', once: true },
});

// 8. Hero : réécriture des mots mis en valeur
const motsHero = gsap.utils.toArray('.accueil-step-one h2 span');
const nouveauxMots = ['PASSION.', 'AMBITION']; // remplace par tes propres mots

const tlHero = gsap.timeline({ delay: 1.5 });

motsHero.forEach((mot, i) => {
  tlHero
    .to(mot, {
      text: { value: '' },
      duration: 0.6,
      ease: 'none',
    })
    .to(mot, {
      text: { value: nouveauxMots[i] },
      duration: 1,
      ease: 'none',
    });
});