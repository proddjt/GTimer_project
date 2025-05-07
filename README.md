# 🕒 GTimer - Il timer da speedcuber, fatto da uno speedcuber

Ciao! 👋 Mi chiamo **Giovanni Tramontano** e questo è **GTimer**, il mio personale progetto di timer WCA completamente sviluppato in **Laravel**! 🚀

GTimer prende ispirazione da celebri web app del mondo speedcubing come **CSTimer** e **QQTimer**, ma con un tocco personale (e un pizzico di follia 😄).

---

## 🧠 Tech stuff (ma detto semplice)

Questo progetto è sviluppato interamente con:

- **Laravel** (of course 🧡)
- **HTML + Bootstrap** per il layout
- **JavaScript** per tutta la logica del timer
- **PHP** per il back-end
- **Livewire** per rendere tutto super dinamico ⚡
- **Fortify** per la gestione dell’autenticazione

---

## 🌀 Scramble? Ci pensa TNoodle!

Per generare scramble in modo realistico, ho integrato **TNoodle CLI**, il tool ufficiale WCA (quello vero, usato in gara 😎).

Lo lancio in **background con Symfony Process**, così il timer si carica super veloce e senza dover aspettare lo scramble ogni volta.  
Tutto asincrono, tutto fluido 💨

---

## ✋ Partenza stile Stackmat

Niente barra spaziatrice qui!  
Per far partire (e fermare) il timer devi **premere entrambi i tasti CTRL** ⌨️✋✋

Proprio come sullo **Stackmat** ufficiale: mani sui sensori → via col tempo → mani di nuovo → tempo fermato!  
È un dettaglio, ma per chi gareggia davvero… fa tutta la differenza 💪

---

## ⚙️ Come provarlo in locale?

Se vuoi clonar(ti) GTimer e farlo girare sul tuo PC, segui questi step:

1. Clona il repository
2. Scarica **[tnoodle-cli da GitHub](https://github.com/SpeedcuberOSS/tnoodle-cli)** e mettilo nella root del progetto (`/tnoodle-cli-win_x64`)
3. Installa i pacchetti:
   ```bash
   composer install
   npm install
   ```
4. Installa **Fortify** e **Livewire**
5. Migra e seed-a il database:
   ```bash
   php artisan migrate --seed
   ```
6. Avvia i 3 comandi fondamentali:
   ```bash
   php artisan serve        # per il server Laravel
   npm run dev              # per gli asset front-end
   php artisan queue:work   # per gestire gli scramble in coda
   ```

Se segui tutto alla lettera, sei pronto a cubare ⏱️🧊

---

## 🎨 Work in progress...

Il progetto è ancora **in fase di sviluppo attivo**!  
L'obiettivo è realizzare un **frontend sempre più figo e user-friendly**, perfetto per allenarsi, simulare gare o semplicemente divertirsi a cronometrare solve su solve.

---

## ❤️ Supporto & feedback

Hai idee, suggerimenti o vuoi contribuire?  
Apri una **issue** o mandami una **pull request** — ogni aiuto è super apprezzato!

---

Stay cubed 🤙  
**Giovanni Tramontano**