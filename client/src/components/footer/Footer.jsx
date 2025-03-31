
export default function Footer() {
    return (
        <footer>
            <div className="mt-20 min-h-[120px] flex items-center justify-center bg-slate-700">
              <svg width="100%" height="100%" viewBox="-10.5 -9.45 21 18.9" fill="none" xmlns="http://www.w3.org/2000/svg" className="uwu-hidden mt-4 mb-3 text-brand dark:text-brand-dark w-24 lg:w-28 self-center text-sm me-0 flex origin-center transition-all ease-in-out"><circle cx="0" cy="0" r="2" fill="rgb(88, 196, 220)"></circle><g stroke="rgb(88, 196, 220)" strokeWidth="1" fill="none"><ellipse rx="10" ry="4.5"></ellipse><ellipse rx="10" ry="4.5" transform="rotate(60)"></ellipse><ellipse rx="10" ry="4.5" transform="rotate(120)"></ellipse></g></svg>
            </div>
            <div className="min-h-[60px] flex items-center justify-center text-center text-white bg-slate-500">Copyright 1993 – 2025 © ReactJS Softuni - February 2025.</div>
            <script src="./public/js/wow.min.js"></script>
              <script>
                new WOW().init();
              </script>
        </footer>
    );
}