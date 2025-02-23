tailwind.config = {
    theme: {
        fontFamily: {
            primary: ["var(--font-primary)"],
        },
        extend: {
            container: {
                center: true,
                padding: {
                    DEFAULT: "0.5rem",
                    sm: "0",
                },
            },
            screens: {
                "2xl": "1400px",
                xsm: "460px",
            },
            colors: {
                primary: "#FD740F",
                "rustic-red": "#5A0000",
                "light-yellow": "#FFB321",
                "persian-blue": "#002D58",
                "persian-red": "#D73421",
                "jet-gray": "#5F6C72",
                "butterfly-blue": "#2DA5F3",
                "rangoon-green": "#191C1F",
                "slime-green": "#BBEA70",
                "aqua-deep": "#00573A",
                "leaf-green": "#66BC03",
                "sand-brown": "#D99F46",
                "theme-teal": "#239698",
                "davy-gray": "#4A5568",
                "theme-light": "#F8F8F8",
                "theme-dark": "#232321",
            },
        },
    },
};
