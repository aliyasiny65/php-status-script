let sistemtema = window.matchMedia('(prefers-color-scheme: dark)').matches;
if (sistemtema) {
    document.getElementsByTagName('body')[0].classList.toggle("dark");
} else if (!sistemtema) {
}