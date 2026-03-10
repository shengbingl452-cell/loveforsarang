const menuZhCute = [
    "排骨汤", "方鱼", "炒鸡蛋", "薄煎饼培根豆", "冷面", "猪排", 
    "秋尾乌冬面", "巧克力香蕉华夫饼", "草莓蛋糕", "寿司", 
    "便利店鱼饼", "火鸡味土豆饼干", "巧克力饼干", "酸奶软糖", 
    "荞麦冷面", "甜点芒果", "辣白菜炒年糕", "米肠汤饭", 
    "地瓜饼干", "牛排", "鲷鱼烧", "玉子烧", "鱿鱼", "鸡肉炒饭", 
    "辣炖鸡块", "水果茶", "沙拉意大利面", "希腊酸奶", "百吉 bagel", 
    "小鱿鱼火锅", "火鸡面", "海带汤面", "草莓芝士蛋糕", "火锅", 
    "年糕汤", "草莓糖葫芦", "青阳辣椒意大利面", "开心果蛋挞", 
    "炸薯条", "烤肉", "奶油虾", "西红柿炒鸡蛋", "泰式炒米粉", 
    "炸鸡", "鱿鱼玫瑰炒年糕", "牛骨汤", "烤串", "牛尾肉", 
    "大酱汤", "栗子", "麻辣烫", "巧克力冰淇淋", "辛奇火腿炒饭", 
    "咖喱包饭", "果冻", "豆粘锅", "杂菜煎饼", "油炸食品小菜", 
    "草莓巧克力", "甜米露", "橘子", "盐面包", "鳗鱼盖饭", 
    "哈密瓜面包", "牛角面包", "紫菜包饭", "芝士条", 
    "番茄奶油炖鸡拌饭", "草莓巧克力糯米糕", "鱼饼", 
    "鱿鱼辣椒酱加蛋黄酱", "干菜大酱汤", "年糕串", "海鲜面"
];

const menuByLang = {
    zh: menuZhCute,
    en: [
        "Ribs soup", "Square fish", "Fried eggs", "Bacon beans crepe", "Cold noodles", "Pork cutlet",
        "Autumn salmon udon", "Choco-banana waffles", "Strawberry cake", "Sushi",
        "Convenience fish cake", "Turkey-flavored chips", "Choco cookies", "Yogurt gummies",
        "Buckwheat 냉면", "Mango sweetie", "Kimchi 떡볶이", "Sundae soup",
        "Sweet potato chips", "Steak", "Taiyaki", "Tamagoyaki", "Squid", "Chicken fried rice",
        "Spicy braised chicken", "Fruit tea", "Salad pasta", "Greek yogurt", "Bagel buddy",
        "Tiny squid hotpot", "Fire noodles", "Seaweed soup noodles", "Strawberry cheesecake", "Hotpot",
        "Rice cake soup", "Strawberry tanghulu", "Cheongyang chili pasta", "Pistachio egg tart",
        "French fries", "BBQ", "Creamy shrimp", "Tomato & egg stir-fry", "Thai rice noodles",
        "Fried chicken", "Rose 떡볶이 with squid", "Beef bone soup", "Skewers", "Oxtail",
        "Doenjang soup", "Chestnuts", "Malatang", "Choco ice cream", "Kimchi ham fried rice",
        "Curry rice", "Jelly", "Dwenjang hotpot", "Japchae pancake", "Fried snack set",
        "Strawberry choco", "Sweet rice drink", "Mandarins", "Salt bread", "Eel rice bowl",
        "Melon bread", "Croissant", "Gimbap", "Cheese sticks",
        "Tomato-cream chicken bowl", "Strawberry choco mochi", "Fish cake",
        "Squid + gochujang & yolk mayo", "Dried veggie 된장 soup", "Skewered tteok", "Seafood noodles"
    ],
    ko: [
        "갈비탕", "방어", "계란볶음", "베이컨콩 크레페", "냉면", "돈가스",
        "가을연어 우동", "초코바나나 와플", "딸기케이크", "스시",
        "편의점 어묵", "칠면조맛 과자", "초코 쿠키", "요거트 젤리",
        "메밀 냉면", "망고 디저트", "김치 떡볶이", "순대국",
        "고구마 과자", "스테이크", "붕어빵", "타마고야키", "오징어", "닭볶음밥",
        "매콤 닭찜", "과일차", "샐러드 파스타", "그릭요거트", "베이글 친구",
        "꼬마 오징어 전골", "불닭면", "미역국 면", "딸기치즈케이크", "전골",
        "떡국", "딸기 탕후루", "청양고추 파스타", "피스타치오 에그타르트",
        "감자튀김", "바비큐", "크리미 새우", "토마토계란볶음", "태국식 볶음면",
        "치킨", "오징어 로제 떡볶이", "사골국", "꼬치", "우꼬리",
        "된장국", "밤", "마라탕", "초코 아이스크림", "김치햄볶음밥",
        "카레라이스", "젤리", "된장 전골", "잡채전", "튀김 모둠",
        "딸기 초코", "식혜", "귤", "소금빵", "장어덮밥",
        "멜론빵", "크루아상", "김밥", "치즈스틱",
        "토마토크림 닭덮밥", "딸기초코 찹쌀떡", "어묵",
        "오징어+초고추장+노른자 마요", "건채소 된장국", "떡꼬치", "해물면"
    ]
};

function getMenuList(lang) {
    return menuByLang[lang] || menuByLang.zh;
}
