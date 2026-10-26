# -*- coding: utf-8 -*-
"""Translate the public business-network form chrome. Option values stay English."""
import re
from pathlib import Path

ROOT = Path(r"c:\xampp\htdocs\P23 Africa")
FORM = ROOT / "resources/views/brn-form.blade.php"

ROWS = """
Software and SaaS Solutions|Solutions logicielles et SaaS|Software- und SaaS-Lösungen|Software- en SaaS-oplossingen
FinTech and Digital Payment Systems|Fintech et paiements numériques|FinTech und digitale Zahlungssysteme|Fintech en digitale betaalsystemen
Cybersecurity and Data Protection Services|Cybersécurité et protection des données|Cybersicherheit und Datenschutz|Cybersecurity en gegevensbescherming
Cloud Computing and IT Infrastructure|Cloud et infrastructure informatique|Cloud-Computing und IT-Infrastruktur|Cloudcomputing en IT-infrastructuur
Artificial Intelligence and Machine Learning|Intelligence artificielle et apprentissage automatique|Künstliche Intelligenz und maschinelles Lernen|Kunstmatige intelligentie en machine learning
Telecommunications and Network Services|Télécommunications et réseaux|Telekommunikation und Netzdienste|Telecommunicatie en netwerkdiensten
E-commerce and Digital Platforms|Commerce en ligne et plateformes numériques|E-Commerce und digitale Plattformen|E-commerce en digitale platforms
Blockchain and Digital Asset Management|Blockchain et gestion d’actifs numériques|Blockchain und digitale Vermögenswerte|Blockchain en beheer van digitale activa
Banking and Financial Institutions|Banques et institutions financières|Banken und Finanzinstitute|Banken en financiële instellingen
Investment and Asset Management|Investissement et gestion d’actifs|Investment und Vermögensverwaltung|Beleggen en vermogensbeheer
Insurance and Risk Advisory Services|Assurance et conseil en risques|Versicherung und Risikoberatung|Verzekeringen en risicoadvies
Legal and Regulatory Compliance Services|Services juridiques et de conformité|Recht und regulatorische Compliance|Juridische diensten en compliance
Accounting and Tax Advisory Services|Comptabilité et conseil fiscal|Rechnungswesen und Steuerberatung|Accountancy en belastingadvies
Management Consulting|Conseil en management|Unternehmensberatung|Managementadvies
Marketing and Advertising Agencies|Agences de marketing et de publicité|Marketing- und Werbeagenturen|Marketing- en reclamebureaus
Human Resources and Talent Acquisition|Ressources humaines et recrutement|Personalwesen und Talentgewinnung|HR en werving
Industrial Machinery and Equipment|Machines et équipements industriels|Industriemaschinen und Ausrüstung|Industriële machines en apparatuur
Automotive and Aerospace Manufacturing|Construction automobile et aérospatiale|Automobil- und Luftfahrtfertigung|Auto- en luchtvaartproductie
Electronics and Technology Components|Composants électroniques et technologiques|Elektronik- und Technologiekomponenten|Elektronica en technologiecomponenten
Chemicals and Raw Materials Production|Produits chimiques et matières premières|Chemie und Rohstoffe|Chemicaliën en grondstoffen
Construction Materials and Supplies|Matériaux et fournitures de construction|Baustoffe und Baumaterial|Bouwmaterialen en benodigdheden
Packaging and Container Manufacturing|Emballage et contenants|Verpackung und Behälter|Verpakkingen en containers
Medical Devices and Equipment Production|Dispositifs et équipements médicaux|Medizinprodukte und Geräte|Medische hulpmiddelen en apparatuur
Textiles and Apparel Manufacturing|Textile et habillement|Textil- und Bekleidungsfertigung|Textiel en kleding
Pharmaceuticals and Biotechnology|Pharmacie et biotechnologie|Pharma und Biotechnologie|Farmacie en biotechnologie
Hospitals and Clinical Healthcare Facilities|Hôpitaux et établissements de soins|Krankenhäuser und Kliniken|Ziekenhuizen en klinieken
Medical Equipment and Supplies|Équipements et fournitures médicaux|Medizinische Geräte und Bedarf|Medische apparatuur en benodigdheden
Healthcare Technology Systems|Systèmes technologiques de santé|Gesundheitstechnologie|Zorgtechnologie
Laboratory and Diagnostic Services|Laboratoires et diagnostic|Labor- und Diagnostikdienste|Laboratoria en diagnostiek
Senior Care and Rehabilitation Services|Soins aux seniors et réadaptation|Seniorenpflege und Rehabilitation|Ouderenzorg en revalidatie
Telemedicine and Digital Health Solutions|Télémédecine et santé numérique|Telemedizin und digitale Gesundheit|Telemedicine en digitale zorg
Clinical Research and Trials|Recherche clinique et essais|Klinische Forschung und Studien|Klinisch onderzoek en trials
Oil, Gas, and Petroleum|Pétrole, gaz et hydrocarbures|Öl, Gas und Erdöl|Olie, gas en petroleum
Renewable Energy (Solar, Wind, Hydroelectric)|Énergies renouvelables (solaire, éolien, hydraulique)|Erneuerbare Energie (Sonne, Wind, Wasser)|Hernieuwbare energie (zon, wind, water)
Power Generation and Distribution|Production et distribution d’électricité|Stromerzeugung und -verteilung|Opwekking en distributie van stroom
Utilities and Water Services Management|Services publics et gestion de l’eau|Versorgung und Wasserdienste|Nutsbedrijven en waterbeheer
Mining and Natural Resources Extraction|Mines et ressources naturelles|Bergbau und Rohstoffgewinnung|Mijnbouw en grondstoffen
Energy Storage and Management Systems|Stockage et gestion de l’énergie|Energiespeicherung und -management|Energieopslag en -beheer
Environmental and Sustainability Services|Services environnementaux et de durabilité|Umwelt- und Nachhaltigkeitsdienste|Milieu- en duurzaamheidsdiensten
Wholesale and Distribution Networks|Grossiste et réseaux de distribution|Großhandel und Vertriebsnetze|Groothandel en distributienetwerken
Retail Chains and Franchising Operations|Chaînes de magasins et franchise|Einzelhandelsketten und Franchising|Winkelketens en franchise
Food and Beverage Production|Production alimentaire et de boissons|Lebensmittel- und Getränkeherstellung|Voedsel- en drankproductie
Consumer Electronics|Électronique grand public|Unterhaltungselektronik|Consumentenelektronica
Fashion and Luxury Goods|Mode et produits de luxe|Mode und Luxusgüter|Mode en luxe
Home Goods and Appliances|Articles pour la maison et électroménager|Haushaltswaren und Geräte|Huishoudelijke artikelen en apparaten
Sports and Leisure Products|Articles de sport et de loisirs|Sport- und Freizeitartikel|Sport- en vrijetijdsproducten
Shipping and Freight Forwarding Services|Transport maritime et commission de transport|Schifffahrt und Spedition|Zeevracht en expeditie
Air Cargo and Logistics|Fret aérien et logistique|Luftfracht und Logistik|Luchtvracht en logistiek
Trucking and Ground Transportation|Transport routier|Lkw- und Landtransport|Wegtransport
Warehousing and Storage Facilities|Entreposage et stockage|Lagerung und Speicher|Opslag en warehousing
Supply Chain Management|Gestion de la chaîne d’approvisionnement|Lieferkettenmanagement|Supplychainmanagement
Fleet and Vehicle Management|Gestion de flotte et de véhicules|Flotten- und Fahrzeugmanagement|Wagenpark- en voertuigbeheer
Ports and Terminal Operations|Ports et terminaux|Häfen und Terminals|Havens en terminals
Farming and Agricultural Production|Agriculture et production agricole|Landwirtschaft und Agrarproduktion|Landbouw en agrarische productie
Food Processing and Manufacturing|Transformation alimentaire|Lebensmittelverarbeitung|Voedselverwerking
Beverage Production|Production de boissons|Getränkeherstellung|Drankenproductie
Agricultural Technology (AgriTech)|Technologies agricoles (AgriTech)|Agrartechnologie (AgriTech)|Landbouwtechnologie (AgriTech)
Livestock and Animal Products|Élevage et produits animaux|Viehzucht und Tierprodukte|Veehouderij en dierlijke producten
Commodity Trading and Export|Négoce et export de matières premières|Rohstoffhandel und Export|Grondstoffenhandel en export
Agricultural Equipment and Supplies|Équipements et fournitures agricoles|Landwirtschaftliche Geräte und Bedarf|Landbouwmachines en benodigdheden
Commercial Real Estate Development|Promotion immobilière commerciale|Gewerbliche Immobilienentwicklung|Commerciële vastgoedontwikkeling
Property Management Services|Gestion immobilière|Immobilienverwaltung|Vastgoedbeheer
Construction and Engineering Services|Construction et ingénierie|Bau und Ingenieurwesen|Bouw en engineering
Architecture and Design Services|Architecture et design|Architektur und Design|Architectuur en ontwerp
Facility Management|Gestion des installations|Gebäudemanagement|Facilitair beheer
Real Estate Investment|Investissement immobilier|Immobilieninvestitionen|Vastgoedinvesteringen
Building Materials and Supplies|Matériaux de construction|Baustoffe|Bouwmaterialen
Broadcasting and Entertainment|Audiovisuel et divertissement|Rundfunk und Unterhaltung|Omroep en entertainment
Digital Media and Publishing|Médias numériques et édition|Digitale Medien und Verlagswesen|Digitale media en uitgeverij
Advertising and Public Relations|Publicité et relations publiques|Werbung und Öffentlichkeitsarbeit|Reclame en public relations
Telecommunications Services|Services de télécommunications|Telekommunikationsdienste|Telecommunicatiediensten
Content Creation and Production|Création et production de contenus|Inhaltserstellung und Produktion|Contentcreatie en productie
Event Management and Production|Organisation et production d’événements|Eventmanagement und Produktion|Evenementenbeheer en productie
Corporate Training and Development|Formation et développement en entreprise|Betriebliche Weiterbildung|Bedrijfstraining en ontwikkeling
Educational Technology (EdTech)|Technologies éducatives (EdTech)|Bildungstechnologie (EdTech)|Onderwijstechnologie (EdTech)
Schools and Educational Institutions|Écoles et établissements d’enseignement|Schulen und Bildungseinrichtungen|Scholen en onderwijsinstellingen
Professional Certification Programs|Programmes de certification professionnelle|Berufliche Zertifizierungsprogramme|Professionele certificeringsprogramma’s
Language and Skills Training|Formation linguistique et compétences|Sprach- und Kompetenztraining|Taal- en vaardigheidstraining
E-learning and Online Education|Formation en ligne|E-Learning und Online-Bildung|E-learning en online onderwijs
Government and Public Administration|Administration publique|Öffentliche Verwaltung|Overheid en openbaar bestuur
Defense and Security Services|Défense et sécurité|Verteidigung und Sicherheit|Defensie en beveiliging
Infrastructure Development|Développement des infrastructures|Infrastrukturentwicklung|Infrastructuurontwikkeling
Municipal and Public Utilities|Services municipaux et publics|Kommunale und öffentliche Versorgung|Gemeentelijke en openbare nutsvoorzieningen
Public Health Services|Santé publique|Öffentliche Gesundheit|Volksgezondheid
Government Technology Solutions|Solutions technologiques pour le secteur public|Technologie für die öffentliche Hand|Technologie voor de overheid
Waste Management and Recycling|Gestion des déchets et recyclage|Abfallwirtschaft und Recycling|Afvalbeheer en recycling
Water Treatment and Management|Traitement et gestion de l’eau|Wasseraufbereitung und -management|Waterzuivering en -beheer
Environmental Consulting|Conseil environnemental|Umweltberatung|Milieuadvies
Engineering and Technical Services|Ingénierie et services techniques|Ingenieur- und technische Dienste|Engineering en technische diensten
Research and Development|Recherche et développement|Forschung und Entwicklung|Onderzoek en ontwikkeling
Testing and Quality Assurance|Tests et assurance qualité|Prüfung und Qualitätssicherung|Testen en kwaliteitsborging
""".strip()


def php_str(value: str) -> str:
    return "'" + value.replace("\\", "\\\\").replace("'", "\\'") + "'"


def categories(lang_index: int) -> str:
    lines = ["    'categories' => ["]
    for row in ROWS.splitlines():
        parts = row.split("|")
        en, translated = parts[0], parts[lang_index]
        lines.append(f"        {php_str(en)} => {php_str(translated)},")
    lines.append("    ],")
    return "\n".join(lines)


EXTRA = {
    "en": """
    'err_year_invalid' => 'Please enter a valid year',
    'err_employees' => 'Please select number of employees',
    'err_revenue_select' => 'Please select revenue range',
    'err_goals' => 'Please describe your company',
    'err_interest' => 'Please select your interest',
    'no_country' => 'No country found',
""",
    "fr": """
    'err_year_invalid' => 'Veuillez saisir une année valide',
    'err_employees' => 'Veuillez choisir le nombre d’employés',
    'err_revenue_select' => 'Veuillez choisir une tranche de chiffre d’affaires',
    'err_goals' => 'Veuillez décrire votre entreprise',
    'err_interest' => 'Veuillez choisir votre intérêt',
    'no_country' => 'Aucun pays trouvé',
""",
    "de": """
    'err_year_invalid' => 'Bitte geben Sie ein gültiges Jahr ein',
    'err_employees' => 'Bitte wählen Sie die Mitarbeiterzahl',
    'err_revenue_select' => 'Bitte wählen Sie einen Umsatzbereich',
    'err_goals' => 'Bitte beschreiben Sie Ihr Unternehmen',
    'err_interest' => 'Bitte wählen Sie Ihr Interesse',
    'no_country' => 'Kein Land gefunden',
""",
    "nl": """
    'err_year_invalid' => 'Vul een geldig jaar in',
    'err_employees' => 'Kies het aantal medewerkers',
    'err_revenue_select' => 'Kies een omzetbereik',
    'err_goals' => 'Beschrijf uw bedrijf',
    'err_interest' => 'Kies uw interesse',
    'no_country' => 'Geen land gevonden',
""",
}


def load(path: Path):
    raw = path.read_bytes()
    nl = "\r\n" if b"\r\n" in raw else "\n"
    return raw.decode("utf-8").replace("\r\n", "\n"), nl


def save(path: Path, text: str, nl: str):
    path.write_bytes(text.replace("\n", nl).encode("utf-8"))


def append_php(rel, marker, snippet):
    path = ROOT / rel
    text, nl = load(path)
    if marker in text:
        print("SKIP", rel, marker)
        return
    idx = text.rfind("];")
    text = text[:idx] + snippet.rstrip() + "\n" + text[idx:]
    save(path, text, nl)
    print("APPEND", rel, marker)


def sub(text, old, new, label):
    n = text.count(old)
    if n == 0:
        print("MISS", label)
        return text
    print(f"OK {n} {label}")
    return text.replace(old, new)


def display(text, english, expr, label):
    pattern = r"(>)(\s*)" + re.escape(english) + r"(\s*)(<)"
    repl = r"\1\2" + expr + r"\3\4"
    text2, n = re.subn(pattern, repl, text)
    if n == 0:
        print("MISS", label)
    else:
        print(f"OK {n} {label}")
    return text2


def main():
    for loc, idx in (("fr", 1), ("de", 2), ("nl", 3)):
        append_php(f"lang/{loc}/brn.php", "'categories'", categories(idx))
        append_php(f"lang/{loc}/brn.php", "'err_year_invalid'", EXTRA[loc])
    append_php("lang/en/brn.php", "'categories'", "    'categories' => [],")
    append_php("lang/en/brn.php", "'err_year_invalid'", EXTRA["en"])

    text, nl = load(FORM)
    pairs = [
        ("Let's start with your basic details", "{{ __('brn.start_basic') }}", "start"),
        ("<p>Stand out among other businesses</p>", "<p>{{ __('brn.stand_out') }}</p>", "stand"),
        ("<p>Boost visibility for your matches</p>", "<p>{{ __('brn.boost') }}</p>", "boost"),
        ('<div class="label">Basic Information</div>', '<div class="label">{{ __(\'brn.basic\') }}</div>', "basic"),
        ('<div class="label">Credibility Check</div>', '<div class="label">{{ __(\'brn.credibility\') }}</div>', "cred"),
        ('<div class="label">Industry Preference</div>', '<div class="label">{{ __(\'brn.preference\') }}</div>', "pref"),
        ('<div class="label">Industry Insight</div>', '<div class="label">{{ __(\'brn.insight\') }}</div>', "insight"),
        ('<label for="fullName">Full Name</label>', '<label for="fullName">{{ __(\'brn.full_name\') }}</label>', "full"),
        ('placeholder="Full name"', 'placeholder="{{ __(\'brn.ph_name\') }}"', "ph name"),
        ('<label for="title">Title</label>', '<label for="title">{{ __(\'brn.title_label\') }}</label>', "title"),
        ("Title, e.g CEO", "{{ __('brn.title_eg') }}", "title eg"),
        ('<label for="businessName">Official Business Name</label>', '<label for="businessName">{{ __(\'brn.business_name\') }}</label>', "biz"),
        ('placeholder="Business Name"', 'placeholder="{{ __(\'brn.ph_business\') }}"', "ph biz"),
        ('<label for="emailAddress">Email Address</label>', '<label for="emailAddress">{{ __(\'brn.email\') }}</label>', "email"),
        ('placeholder="Email Address"', 'placeholder="{{ __(\'brn.ph_email\') }}"', "ph email"),
        ("✓ Email", "✓ {{ __('brn.email_ok') }}", "email ok"),
        ("⏳", "⏳", "skip"),
        ('<label for="phoneNumber">Phone Number</label>', '<label for="phoneNumber">{{ __(\'brn.phone\') }}</label>', "phone"),
        ('placeholder="Search country..."', 'placeholder="{{ __(\'brn.ph_search_country\') }}"', "search c"),
        ('placeholder="Select country code first"', 'placeholder="{{ __(\'brn.ph_code_first\') }}"', "code"),
        ('placeholder="Enter phone number"', 'placeholder="{{ __(\'brn.ph_phone\') }}"', "ph phone"),
        ('<label for="regNumber">Company Registration Number</label>', '<label for="regNumber">{{ __(\'brn.reg_number\') }}</label>', "reg"),
        ('placeholder="Input you company Reg. Number"', 'placeholder="{{ __(\'brn.ph_reg\') }}"', "ph reg"),
        ('<label for="countryReg">Country Of Registration</label>', '<label for="countryReg">{{ __(\'brn.country_reg\') }}</label>', "country"),
        ('placeholder="Search countries..."', 'placeholder="{{ __(\'brn.ph_search_countries\') }}"', "search cs"),
        ("Select your country", "{{ __('brn.select_country') }}", "select country"),
        ('<label for="website">Company Website or Social Handle</label>', '<label for="website">{{ __(\'brn.website\') }}</label>', "web"),
        ('placeholder="Input your company website/socials"', 'placeholder="{{ __(\'brn.ph_website\') }}"', "ph web"),
        ('<label for="linkedin">LinkedIn Profile</label>', '<label for="linkedin">{{ __(\'brn.linkedin\') }}</label>', "li"),
        ('placeholder="Add your profile Url (Optional)"', 'placeholder="{{ __(\'brn.ph_profile\') }}"', "ph profile"),
        ("I don’t have a LinkedIn account.", "{{ __('brn.no_linkedin') }}", "no li"),
        ('<label for="yearEst">Year of Establishment</label>', '<label for="yearEst">{{ __(\'brn.year\') }}</label>', "year"),
        ('placeholder="Input year of establishment"', 'placeholder="{{ __(\'brn.ph_year\') }}"', "ph year"),
        ('<label for="industry">What industry does your business operate in?</label>', '<label for="industry">{{ __(\'brn.industry_q\') }}</label>', "ind q"),
        ("Select your industry", "{{ __('brn.select_industry') }}", "sel ind"),
        ('<label for="employees">Numbers of Employees</label>', '<label for="employees">{{ __(\'brn.employees\') }}</label>', "emp"),
        ("Select Employees range", "{{ __('brn.select_employees') }}", "emp range"),
        ("Approximate annual revenue", "{{ __('brn.revenue') }}", "rev"),
        ("(confidential – for internal use only)", "{{ __('brn.confidential') }}", "conf"),
        ("Select revenue range", "{{ __('brn.select_revenue') }}", "rev range"),
        ('placeholder="Enter your value"', 'placeholder="{{ __(\'brn.ph_value\') }}"', "ph val"),
        ('placeholder="Enter your description"', 'placeholder="{{ __(\'brn.ph_desc\') }}"', "ph desc"),
        ('<label for="interest">What are you most interested in from this network?</label>', '<label for="interest">{{ __(\'brn.interest_q\') }}</label>', "int q"),
        ("Select interest", "{{ __('brn.select_interest') }}", "sel int"),
        ('<label for="opt-yes">Yes, I\'m interested</label>', '<label for="opt-yes">{{ __(\'brn.yes\') }}</label>', "yes"),
        ('<label for="opt-maybe">Maybe, send me more information</label>', '<label for="opt-maybe">{{ __(\'brn.maybe\') }}</label>', "maybe"),
        ('<label for="opt-no">No, not interested</label>', '<label for="opt-no">{{ __(\'brn.no\') }}</label>', "no"),
        (">Submit<", ">{{ __('brn.submit') }}<", "submit"),
        (">Done<", ">{{ __('brn.done') }}<", "done"),
        ("Contact Information", "{{ __('brn.contact_info') }}", "contact"),
        ("showError(name, 'Full name is required')", "showError(name, @json(__('brn.err_name')))", "e name"),
        ("showError(email, 'Email address is required')", "showError(email, @json(__('brn.err_email')))", "e email"),
        ("showError(email, 'Please enter a valid email address')", "showError(email, @json(__('brn.err_email_invalid')))", "e email2"),
        ('error.textContent = "Phone number is required"', 'error.textContent = @json(__(\'brn.err_phone\'))', "e phone"),
        ("showError(customSelect || position.parentNode, 'Please select your title')", "showError(customSelect || position.parentNode, @json(__('brn.err_title')))", "e title"),
        ("showError(companyName, 'Company name is required')", "showError(companyName, @json(__('brn.err_company')))", "e co"),
        ("showError(regNumber, 'Company registration number is required')", "showError(regNumber, @json(__('brn.err_reg')))", "e reg"),
        ("showError(customSelect || country.parentNode, 'Country of registration is required')", "showError(customSelect || country.parentNode, @json(__('brn.err_country')))", "e country"),
        ("showError(website, 'Company website or social handle is required')", "showError(website, @json(__('brn.err_website')))", "e web"),
        ("showError(linkedin, 'Please enter a valid LinkedIn profile URL')", "showError(linkedin, @json(__('brn.err_linkedin')))", "e li"),
        ("showError(yearEstablished, 'Year established is required')", "showError(yearEstablished, @json(__('brn.err_year')))", "e year"),
        ("showError(yearEstablished, 'Please enter a valid year')", "showError(yearEstablished, @json(__('brn.err_year_invalid')))", "e year2"),
        ("showError(customSelect || employees.parentNode, 'Please select number of employees')", "showError(customSelect || employees.parentNode, @json(__('brn.err_employees')))", "e emp"),
        ("showError(customSelect || revenue.parentNode, 'Please select revenue range')", "showError(customSelect || revenue.parentNode, @json(__('brn.err_revenue_select')))", "e rev"),
        ("showError(goals, 'Please describe your company')", "showError(goals, @json(__('brn.err_goals')))", "e goals"),
        ("showError(customSelect || selectedOutcome.parentNode, 'Please select your interest')", "showError(customSelect || selectedOutcome.parentNode, @json(__('brn.err_interest')))", "e int"),
        ("noResults.textContent = 'No results found'", "noResults.textContent = @json(__('brn.no_results'))", "no res"),
        ('noCountryItem.textContent = "No country found"', 'noCountryItem.textContent = @json(__(\'brn.no_country\'))', "no country"),
        ("You’ve just taken a big step toward meaningful business growth!", "{{ __('brn.thanks_title') }}", "thanks"),
        ("Check your email for next steps and more details from NOEL.", "{{ __('brn.thanks_body') }}", "thanks body"),
        ("let errorMessage = 'Something went wrong. Please try again.';", "let errorMessage = @json(__('brn.err_generic'));", "generic"),
        ("'Your session has expired. Click the button below to refresh and try again.'", "@json(__('brn.err_session'))", "session"),
        ("'Please correct the errors below and try again.'", "@json(__('brn.err_correct'))", "correct"),
        ("errorMessage = 'Access denied. Please refresh the page and try again.';", "errorMessage = @json(__('brn.err_denied'));", "denied"),
        ("errorMessage = 'Too many attempts. Please wait a few minutes and try again.';", "errorMessage = @json(__('brn.err_rate'));", "rate"),
        ("'Network error. Please check your internet connection and try again.'", "@json(__('brn.err_network'))", "net"),
        ("Refresh Page", "{{ __('brn.refresh') }}", "refresh"),
        ("|| 'Select option'", "|| @json(__('brn.select_option'))", "option"),
        ("phoneInput.placeholder = \"Select country code first\"", "phoneInput.placeholder = @json(__('brn.ph_code_first'))", "js code"),
        ("phoneInput.placeholder = `Enter phone number`", "phoneInput.placeholder = @json(__('brn.ph_phone'))", "js phone"),
    ]
    # Validating is split across lines
    text2, n = re.subn(r"Validating\.\.\.", "{{ __('brn.validating') }}", text)
    print(("OK " if n else "MISS ") + f"{n} validating")
    text = text2
    text2, n = re.subn(
        r"available</span>",
        "{{ __('brn.email_ok') }}</span>",
        text,
        count=0,
    )
    # The earlier pair already changed "✓ Email" which may have split "available". Check later.
    text = text2

    for old, new, label in pairs:
        if label == "skip":
            continue
        text = sub(text, old, new, label)

    text2, n = re.subn(
        r"NOEL transforms executive networking from casual connections\s*to strategic growth\. Our platform facilitates verified,\s*high-value introductions between senior leaders and\s*investors\. Focus on building relationships that drive real\s*business outcomes\.",
        "{{ __('brn.intro_body') }}",
        text,
    )
    print(f"OK {n} intro" if n else "MISS intro")
    text = text2

    text2, n = re.subn(
        r"What value can your company offer to\s*other CEOs in the\s*network\?",
        "{{ __('brn.offer_q') }}",
        text,
    )
    print(f"OK {n} offer" if n else "MISS offer")
    text = text2

    text2, n = re.subn(
        r"Briefly describe what your\s*company does and who your clients/customers are \(max 150 words\)",
        "{{ __('brn.describe') }}",
        text,
    )
    print(f"OK {n} describe" if n else "MISS describe")
    text = text2

    text2, n = re.subn(
        r"If your business isn’t eligible for the CEO Referral Network at this time, would you be interested in joining our B2B platform to access:",
        "{{ __('brn.fallback_q') }}",
        text,
    )
    print(f"OK {n} fallback" if n else "MISS fallback")
    text = text2

    # Buttons that are their own text node
    for word, key in (("Proceed", "proceed"), ("Return", "return"), ("Submit", "submit")):
        text2, n = re.subn(
            rf"(>)(\s*){word}(\s*)(<)",
            rf"\1\2{{{{ __('brn.{key}') }}}}\3\4",
            text,
        )
        print(f"OK {n} btn {word}" if n else f"MISS btn {word}")
        text = text2

    industries = {
        "Technology & Digital Services": "ind_tech",
        "Financial & Professional Services": "ind_finance",
        "Manufacturing & Industrial": "ind_mfg",
        "Healthcare & Pharmaceuticals": "ind_health",
        "Energy & Utilities": "ind_energy",
        "Retail & Consumer Goods": "ind_retail",
        "Logistics & Transportation": "ind_logistics",
        "Agriculture & Food Processing": "ind_agri",
        "Real Estate & Construction": "ind_property",
        "Media & Communications": "ind_media",
        "Education & Training": "ind_edu",
        "Government & Public Services": "ind_gov",
        "Environmental & Specialized Services": "ind_env",
    }
    for en, key in industries.items():
        text = display(text, en, "{{ __('brn." + key + "') }}", key)

    for en, key in (
        ("Managing Director", "md"),
        ("Founder", "founder"),
        ("Investor", "investor"),
        ("Director", "director"),
        ("Manager", "manager"),
        ("Other", "other"),
        ("Networking", "networking"),
        ("Verified Business Leads", "leads"),
        ("Automated Sales Tools", "tools"),
        ("Consulting Support", "consulting"),
        ("Verified business leads", "fb_leads"),
        ("Automated sales tools", "fb_tools"),
        ("Growth audits and consulting support?", "fb_audits"),
        ("Below $1M", "rev_1"),
        ("$1M - $5M", "rev_2"),
        ("$5M+", "rev_3"),
    ):
        text = display(text, en, "{{ __('brn." + key + "') }}", "opt " + key)

    text = sub(
        text,
        "document.addEventListener('DOMContentLoaded', function() {",
        "document.addEventListener('DOMContentLoaded', function() {\n        const brnCat = @json(__('brn.categories'));",
        "brnCat",
    )
    text = sub(text, "<span>${category}</span>", "<span>${brnCat[category] || category}</span>", "cat span")

    save(FORM, text, nl)
    print("saved form")


if __name__ == "__main__":
    main()
