# -*- coding: utf-8 -*-
"""Wire remaining public Blade copy to existing and new language keys."""
import re
from pathlib import Path

ROOT = Path(r"c:\xampp\htdocs\P23 Africa")


def load(path: Path):
    raw = path.read_bytes()
    nl = "\r\n" if b"\r\n" in raw else "\n"
    return raw.decode("utf-8").replace("\r\n", "\n"), nl


def save(path: Path, text: str, nl: str):
    path.write_bytes(text.replace("\n", nl).encode("utf-8"))


def sub(text, old, new, label):
    count = text.count(old)
    if count == 0:
        print(f"MISS {label}")
        return text
    print(f"OK {count} {label}")
    return text.replace(old, new)


def rsub(text, pattern, new, label, flags=re.S):
    text2, n = re.subn(pattern, new, text, count=1, flags=flags)
    if n == 0:
        print(f"MISS {label}")
    else:
        print(f"OK {n} {label}")
    return text2


def append_php(rel, marker, snippet):
    path = ROOT / rel
    text, nl = load(path)
    if marker in text:
        print(f"SKIP append {rel}")
        return
    idx = text.rfind("];")
    if idx < 0:
        raise SystemExit(f"no close in {rel}")
    text = text[:idx] + snippet.rstrip() + "\n" + text[idx:]
    save(path, text, nl)
    print(f"APPEND {rel}")


def patch(rel, fn):
    path = ROOT / rel
    text, nl = load(path)
    text = fn(text)
    save(path, text, nl)


# --- language keys ---------------------------------------------------------

QUIZ_EN = r"""
    'questions' => [
        ['question' => 'How do you track potential leads and follow-ups?', 'a' => 'We don’t track them', 'b' => 'Sometimes—manually or in spreadsheets', 'c' => 'We have a structured system'],
        ['question' => 'How consistently does your team follow up with warm leads?', 'a' => 'Rarely', 'b' => 'Occasionally', 'c' => 'Always—on a clear schedule'],
        ['question' => 'Do you know exactly where your sales drop off in the customer journey?', 'a' => 'No idea', 'b' => 'A rough idea', 'c' => 'Yes—we’ve analysed it'],
        ['question' => 'Are your team’s targets clear and regularly reviewed?', 'a' => 'No', 'b' => 'Kind of', 'c' => 'Yes—targets are tracked and discussed'],
        ['question' => 'Do you have simple tools to help your team stay organised?', 'a' => 'Not really', 'b' => 'We try—but it’s messy', 'c' => 'Yes—everyone uses them'],
        ['question' => 'How often do you review your sales and marketing performance?', 'a' => 'Rarely or never', 'b' => 'Occasionally', 'c' => 'Monthly or weekly'],
        ['question' => 'Do you feel confident that your business is structured for growth?', 'a' => 'Not at all', 'b' => 'Somewhat', 'c' => 'Definitely'],
        ['question' => 'What’s your biggest challenge right now? (optional)', 'a' => 'Challenge 1', 'b' => 'Challenge 2', 'c' => 'Challenge 3'],
    ],
    'ch1' => 'Challenge 1:',
    'ch2' => 'Challenge 2:',
    'ch3' => 'Challenge 3:',
    'ch1_ph' => 'Type in your first challenge here',
    'ch2_ph' => 'Type in your second challenge here',
    'ch3_ph' => 'Type in your third challenge here',
    'see_results' => 'See Results →',
    'continue_btn' => 'Continue →',
    'invalid_email' => 'Please enter a valid email address.',
    'invalid_name' => 'Please enter your full name.',
    'analysing' => 'Analysing...',
"""

QUIZ_FR = r"""
    'questions' => [
        ['question' => 'Comment suivez-vous les prospects et les relances ?', 'a' => 'Nous ne les suivons pas', 'b' => 'Parfois, à la main ou dans des tableurs', 'c' => 'Nous avons un système structuré'],
        ['question' => 'Avec quelle régularité votre équipe relance-t-elle les prospects chauds ?', 'a' => 'Rarement', 'b' => 'De temps en temps', 'c' => 'Toujours, selon un calendrier clair'],
        ['question' => 'Savez-vous exactement où vos ventes se perdent dans le parcours client ?', 'a' => 'Aucune idée', 'b' => 'Une idée approximative', 'c' => 'Oui, nous l’avons analysé'],
        ['question' => 'Les objectifs de votre équipe sont-ils clairs et revus régulièrement ?', 'a' => 'Non', 'b' => 'En partie', 'c' => 'Oui, les objectifs sont suivis et discutés'],
        ['question' => 'Avez-vous des outils simples pour aider votre équipe à rester organisée ?', 'a' => 'Pas vraiment', 'b' => 'Nous essayons, mais c’est confus', 'c' => 'Oui, tout le monde les utilise'],
        ['question' => 'À quelle fréquence examinez-vous vos performances commerciales et marketing ?', 'a' => 'Rarement ou jamais', 'b' => 'De temps en temps', 'c' => 'Chaque mois ou chaque semaine'],
        ['question' => 'Êtes-vous confiant que votre entreprise est structurée pour croître ?', 'a' => 'Pas du tout', 'b' => 'Un peu', 'c' => 'Tout à fait'],
        ['question' => 'Quel est votre plus grand défi en ce moment ? (facultatif)', 'a' => 'Défi 1', 'b' => 'Défi 2', 'c' => 'Défi 3'],
    ],
    'ch1' => 'Défi 1 :',
    'ch2' => 'Défi 2 :',
    'ch3' => 'Défi 3 :',
    'ch1_ph' => 'Saisissez votre premier défi ici',
    'ch2_ph' => 'Saisissez votre deuxième défi ici',
    'ch3_ph' => 'Saisissez votre troisième défi ici',
    'see_results' => 'Voir les résultats →',
    'continue_btn' => 'Continuer →',
    'invalid_email' => 'Veuillez saisir une adresse e-mail valide.',
    'invalid_name' => 'Veuillez saisir votre nom complet.',
    'analysing' => 'Analyse en cours...',
"""

QUIZ_DE = r"""
    'questions' => [
        ['question' => 'Wie verfolgen Sie potenzielle Leads und Nachfassaktionen?', 'a' => 'Wir verfolgen sie nicht', 'b' => 'Manchmal, manuell oder in Tabellen', 'c' => 'Wir haben ein strukturiertes System'],
        ['question' => 'Wie konsequent folgt Ihr Team warmen Leads nach?', 'a' => 'Selten', 'b' => 'Gelegentlich', 'c' => 'Immer, nach einem klaren Plan'],
        ['question' => 'Wissen Sie genau, wo Verkäufe in der Customer Journey verloren gehen?', 'a' => 'Keine Ahnung', 'b' => 'Eine grobe Vorstellung', 'c' => 'Ja, wir haben es analysiert'],
        ['question' => 'Sind die Ziele Ihres Teams klar und werden sie regelmäßig geprüft?', 'a' => 'Nein', 'b' => 'Teilweise', 'c' => 'Ja, Ziele werden verfolgt und besprochen'],
        ['question' => 'Haben Sie einfache Tools, damit Ihr Team organisiert bleibt?', 'a' => 'Nicht wirklich', 'b' => 'Wir versuchen es, aber es ist unübersichtlich', 'c' => 'Ja, alle nutzen sie'],
        ['question' => 'Wie oft prüfen Sie Vertriebs- und Marketingleistung?', 'a' => 'Selten oder nie', 'b' => 'Gelegentlich', 'c' => 'Monatlich oder wöchentlich'],
        ['question' => 'Sind Sie sicher, dass Ihr Unternehmen auf Wachstum ausgerichtet ist?', 'a' => 'Überhaupt nicht', 'b' => 'Einigermaßen', 'c' => 'Ganz sicher'],
        ['question' => 'Was ist gerade Ihre größte Herausforderung? (optional)', 'a' => 'Herausforderung 1', 'b' => 'Herausforderung 2', 'c' => 'Herausforderung 3'],
    ],
    'ch1' => 'Herausforderung 1:',
    'ch2' => 'Herausforderung 2:',
    'ch3' => 'Herausforderung 3:',
    'ch1_ph' => 'Geben Sie hier Ihre erste Herausforderung ein',
    'ch2_ph' => 'Geben Sie hier Ihre zweite Herausforderung ein',
    'ch3_ph' => 'Geben Sie hier Ihre dritte Herausforderung ein',
    'see_results' => 'Ergebnisse ansehen →',
    'continue_btn' => 'Weiter →',
    'invalid_email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
    'invalid_name' => 'Bitte geben Sie Ihren vollständigen Namen ein.',
    'analysing' => 'Analyse läuft...',
"""

QUIZ_NL = r"""
    'questions' => [
        ['question' => 'Hoe volgt u potentiële leads en opvolging?', 'a' => 'Wij volgen ze niet', 'b' => 'Soms, handmatig of in spreadsheets', 'c' => 'Wij hebben een gestructureerd systeem'],
        ['question' => 'Hoe consequent volgt uw team warme leads op?', 'a' => 'Zelden', 'b' => 'Af en toe', 'c' => 'Altijd, volgens een duidelijke planning'],
        ['question' => 'Weet u precies waar verkopen wegvallen in de klantreis?', 'a' => 'Geen idee', 'b' => 'Een globaal idee', 'c' => 'Ja, we hebben het geanalyseerd'],
        ['question' => 'Zijn de doelen van uw team duidelijk en worden ze regelmatig besproken?', 'a' => 'Nee', 'b' => 'Min of meer', 'c' => 'Ja, doelen worden gevolgd en besproken'],
        ['question' => 'Heeft u eenvoudige tools zodat uw team georganiseerd blijft?', 'a' => 'Niet echt', 'b' => 'We proberen het, maar het is rommelig', 'c' => 'Ja, iedereen gebruikt ze'],
        ['question' => 'Hoe vaak beoordeelt u verkoop- en marketingprestaties?', 'a' => 'Zelden of nooit', 'b' => 'Af en toe', 'c' => 'Maandelijks of wekelijks'],
        ['question' => 'Heeft u er vertrouwen in dat uw bedrijf is ingericht voor groei?', 'a' => 'Helemaal niet', 'b' => 'Enigszins', 'c' => 'Zeker'],
        ['question' => 'Wat is op dit moment uw grootste uitdaging? (optioneel)', 'a' => 'Uitdaging 1', 'b' => 'Uitdaging 2', 'c' => 'Uitdaging 3'],
    ],
    'ch1' => 'Uitdaging 1:',
    'ch2' => 'Uitdaging 2:',
    'ch3' => 'Uitdaging 3:',
    'ch1_ph' => 'Typ hier uw eerste uitdaging',
    'ch2_ph' => 'Typ hier uw tweede uitdaging',
    'ch3_ph' => 'Typ hier uw derde uitdaging',
    'see_results' => 'Resultaten bekijken →',
    'continue_btn' => 'Doorgaan →',
    'invalid_email' => 'Vul een geldig e-mailadres in.',
    'invalid_name' => 'Vul uw volledige naam in.',
    'analysing' => 'Analyseren...',
"""

BRN_EN = r"""
    'meta' => 'Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth.',
    'meta_generator' => 'Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth | P23 Africa',
    'where' => 'Where Top CEOs',
    'connect_grow' => 'Connect and Grow',
    'intro' => 'A private, trusted platform for vetted CEOs across Africa to exchange referrals, strategic deals, and build partnerships through direct 1:1 connections.',
    'register' => 'Register Now!',
    'how' => 'How We Operate',
    'trial' => 'Apply, get approved, and start your 14-day free trial.',
    'connect_body' => 'Browse the vetted directory, get matched with the right professionals, and share referrals.',
    'collaborate_body' => 'Message members directly and grow your network inside an exclusive community.',
    'stories' => 'Real success stories from CEOs who’ve grown through the power of referral-based networking.',
    'role_partner' => 'Managing Partner.',
    'role_ceo' => 'CEO.',
    'role_founder' => 'Founder.',
    'role_director' => 'Director.',
    'quote_linda' => 'This community has opened doors I’d struggled to unlock for years. The calibre of professionals here means every referral actually converts into real business.',
    'quote_kofi' => 'I love that I don’t waste time here. The matching tool introduced me to founders in Ghana and beyond who genuinely want to collaborate and grow together.',
    'quote_chijioke' => 'What sets this apart is the community. Everyone is qualified, serious, and open to real opportunities, not just networking for the sake of it.',
    'quote_zanele' => 'Finally, a trusted network that filters for quality. Every introduction feels intentional, and I’ve already seen a clear ROI through new partnerships.',
    'events_body' => 'From closed-door roundtables to business retreats, every event is designed to foster genuine collaboration and opportunity.',
    'join_body' => 'Join a powerful ecosystem of referrals, relationships, and results.',
    'register_interest' => 'Register Your Interest',
    'start_basic' => 'Let’s start with your basic details',
    'stand_out' => 'Stand out among other businesses',
    'boost' => 'Boost visibility for your matches',
    'intro_body' => 'NOEL transforms executive networking from casual connections to strategic growth. Our platform facilitates verified, high-value introductions between senior leaders and investors. Focus on building relationships that drive real business outcomes.',
    'full_name' => 'Full Name',
    'title_label' => 'Title',
    'title_eg' => 'Title, e.g CEO',
    'founder' => 'Founder',
    'investor' => 'Investor',
    'md' => 'Managing Director',
    'director' => 'Director',
    'manager' => 'Manager',
    'other' => 'Other',
    'business_name' => 'Official Business Name',
    'email' => 'Email Address',
    'email_ok' => 'Email available',
    'validating' => 'Validating...',
    'phone' => 'Phone Number',
    'proceed' => 'Proceed',
    'reg_number' => 'Company Registration Number',
    'country_reg' => 'Country Of Registration',
    'select_country' => 'Select your country',
    'website' => 'Company Website or Social Handle',
    'linkedin' => 'LinkedIn Profile',
    'no_linkedin' => 'I don’t have a LinkedIn account.',
    'year' => 'Year of Establishment',
    'return' => 'Return',
    'industry_q' => 'What industry does your business operate in?',
    'select_industry' => 'Select your industry',
    'employees' => 'Numbers of Employees',
    'select_employees' => 'Select Employees range',
    'revenue' => 'Approximate annual revenue',
    'confidential' => '(confidential – for internal use only)',
    'select_revenue' => 'Select revenue range',
    'rev_1' => 'Below $1M',
    'rev_2' => '$1M - $5M',
    'rev_3' => '$5M+',
    'offer_q' => 'What value can your company offer to other CEOs in the network?',
    'describe' => 'Briefly describe what your company does and who your clients/customers are (max 150 words)',
    'interest_q' => 'What are you most interested in from this network?',
    'select_interest' => 'Select interest',
    'networking' => 'Networking',
    'leads' => 'Verified Business Leads',
    'tools' => 'Automated Sales Tools',
    'consulting' => 'Consulting Support',
    'fallback_q' => 'If your business isn’t eligible for the CEO Referral Network at this time, would you be interested in joining our B2B platform to access:',
    'fb_leads' => 'Verified business leads',
    'fb_tools' => 'Automated sales tools',
    'fb_audits' => 'Growth audits and consulting support?',
    'yes' => 'Yes, I’m interested',
    'maybe' => 'Maybe, send me more information',
    'no' => 'No, not interested',
    'submit' => 'Submit',
    'done' => 'Done',
    'contact_info' => 'Contact Information',
    'ph_name' => 'Full name',
    'ph_business' => 'Business Name',
    'ph_email' => 'Email Address',
    'ph_search_country' => 'Search country...',
    'ph_code_first' => 'Select country code first',
    'ph_reg' => 'Input your company Reg. Number',
    'ph_search_countries' => 'Search countries...',
    'ph_website' => 'Input your company website/socials',
    'ph_profile' => 'Add your profile Url (Optional)',
    'ph_year' => 'Input year of establishment',
    'ph_value' => 'Enter your value',
    'ph_desc' => 'Enter your description',
    'ph_phone' => 'Enter phone number',
    'no_results' => 'No results found',
    'err_name' => 'Full name is required',
    'err_email' => 'Email address is required',
    'err_email_invalid' => 'Please enter a valid email address',
    'err_phone' => 'Phone number is required',
    'err_title' => 'Please select your title',
    'err_company' => 'Company name is required',
    'err_reg' => 'Company registration number is required',
    'err_country' => 'Country of registration is required',
    'err_website' => 'Company website or social handle is required',
    'err_linkedin' => 'Please enter a valid LinkedIn profile URL',
    'err_year' => 'Year established is required',
    'thanks_title' => 'You’ve just taken a big step toward meaningful business growth!',
    'thanks_body' => 'Check your email for next steps and more details from NOEL.',
    'err_generic' => 'Something went wrong. Please try again.',
    'err_session' => 'Your session has expired. Click the button below to refresh and try again.',
    'err_correct' => 'Please correct the errors below and try again.',
    'err_denied' => 'Access denied. Please refresh the page and try again.',
    'err_rate' => 'Too many attempts. Please wait a few minutes and try again.',
    'err_network' => 'Network error. Please check your internet connection and try again.',
    'refresh' => 'Refresh Page',
    'select_option' => 'Select option',
    'ind_tech' => 'Technology & Digital Services',
    'ind_finance' => 'Financial & Professional Services',
    'ind_mfg' => 'Manufacturing & Industrial',
    'ind_health' => 'Healthcare & Pharmaceuticals',
    'ind_energy' => 'Energy & Utilities',
    'ind_retail' => 'Retail & Consumer Goods',
    'ind_logistics' => 'Logistics & Transportation',
    'ind_agri' => 'Agriculture & Food Processing',
    'ind_property' => 'Real Estate & Construction',
    'ind_media' => 'Media & Communications',
    'ind_edu' => 'Education & Training',
    'ind_gov' => 'Government & Public Services',
    'ind_env' => 'Environmental & Specialized Services',
"""

# French, German, Dutch BRN page + form chrome. Industry names are translated for display;
# option values in the form stay English.
BRN_FR = r"""
    'meta' => 'Rejoignez un réseau de recommandations exclusif pour des dirigeants qui privilégient des relations de confiance et une croissance mesurable.',
    'meta_generator' => 'Rejoignez un réseau de recommandations exclusif pour des dirigeants qui privilégient des relations de confiance et une croissance mesurable | P23 Africa',
    'where' => 'Là où les meilleurs dirigeants',
    'connect_grow' => 'se connectent et grandissent',
    'intro' => 'Une plateforme privée et fiable où des dirigeants vérifiés à travers l’Afrique échangent des recommandations, des accords stratégiques et construisent des partenariats en tête-à-tête.',
    'register' => 'Inscrivez-vous',
    'how' => 'Notre façon de travailler',
    'trial' => 'Candidatez, soyez approuvé et commencez votre essai gratuit de 14 jours.',
    'connect_body' => 'Parcourez l’annuaire vérifié, soyez mis en relation avec les bons professionnels et partagez des recommandations.',
    'collaborate_body' => 'Écrivez directement aux membres et développez votre réseau dans une communauté exclusive.',
    'stories' => 'Des réussites concrètes de dirigeants qui ont grandi grâce à un réseau fondé sur les recommandations.',
    'role_partner' => 'Associée gérante.',
    'role_ceo' => 'PDG.',
    'role_founder' => 'Fondateur.',
    'role_director' => 'Directrice.',
    'quote_linda' => 'Cette communauté a ouvert des portes que je n’arrivais pas à franchir depuis des années. Le niveau des professionnels fait que chaque recommandation se transforme en activité réelle.',
    'quote_kofi' => 'J’apprécie de ne pas perdre de temps ici. L’outil de mise en relation m’a présenté des fondateurs au Ghana et au-delà qui veulent vraiment collaborer et grandir ensemble.',
    'quote_chijioke' => 'Ce qui distingue ce réseau, c’est la communauté. Chacun est qualifié, sérieux et ouvert à de vraies opportunités, pas au réseautage pour le principe.',
    'quote_zanele' => 'Enfin un réseau de confiance qui filtre la qualité. Chaque introduction est intentionnelle, et j’ai déjà vu un retour clair grâce à de nouveaux partenariats.',
    'events_body' => 'Des tables rondes fermées aux retraites d’affaires, chaque événement est conçu pour créer une vraie collaboration et de vraies opportunités.',
    'join_body' => 'Rejoignez un écosystème solide de recommandations, de relations et de résultats.',
    'register_interest' => 'Manifester votre intérêt',
    'start_basic' => 'Commençons par vos informations de base',
    'stand_out' => 'Démarquez-vous des autres entreprises',
    'boost' => 'Renforcez la visibilité de vos mises en relation',
    'intro_body' => 'NOEL transforme le réseautage de dirigeants, des contacts occasionnels à une croissance stratégique. La plateforme facilite des introductions vérifiées et à forte valeur entre dirigeants et investisseurs. Concentrez-vous sur des relations qui produisent de vrais résultats.',
    'full_name' => 'Nom complet',
    'title_label' => 'Fonction',
    'title_eg' => 'Fonction, p. ex. PDG',
    'founder' => 'Fondateur',
    'investor' => 'Investisseur',
    'md' => 'Directeur général',
    'director' => 'Directeur',
    'manager' => 'Responsable',
    'other' => 'Autre',
    'business_name' => 'Raison sociale',
    'email' => 'Adresse e-mail',
    'email_ok' => 'E-mail disponible',
    'validating' => 'Vérification...',
    'phone' => 'Numéro de téléphone',
    'proceed' => 'Continuer',
    'reg_number' => 'Numéro d’immatriculation',
    'country_reg' => 'Pays d’immatriculation',
    'select_country' => 'Choisissez votre pays',
    'website' => 'Site web ou réseau social',
    'linkedin' => 'Profil LinkedIn',
    'no_linkedin' => 'Je n’ai pas de compte LinkedIn.',
    'year' => 'Année de création',
    'return' => 'Retour',
    'industry_q' => 'Dans quel secteur votre entreprise opère-t-elle ?',
    'select_industry' => 'Choisissez votre secteur',
    'employees' => 'Nombre d’employés',
    'select_employees' => 'Choisissez une tranche d’effectifs',
    'revenue' => 'Chiffre d’affaires annuel approximatif',
    'confidential' => '(confidentiel – usage interne uniquement)',
    'select_revenue' => 'Choisissez une tranche de chiffre d’affaires',
    'rev_1' => 'Moins de 1 M$',
    'rev_2' => '1 M$ – 5 M$',
    'rev_3' => 'Plus de 5 M$',
    'offer_q' => 'Quelle valeur votre entreprise peut-elle apporter aux autres dirigeants du réseau ?',
    'describe' => 'Décrivez brièvement votre activité et vos clients (150 mots maximum)',
    'interest_q' => 'Qu’attendez-vous le plus de ce réseau ?',
    'select_interest' => 'Choisissez un intérêt',
    'networking' => 'Réseau',
    'leads' => 'Prospects d’affaires vérifiés',
    'tools' => 'Outils de vente automatisés',
    'consulting' => 'Accompagnement conseil',
    'fallback_q' => 'Si votre entreprise n’est pas éligible au réseau de dirigeants pour le moment, souhaiteriez-vous rejoindre notre plateforme B2B pour accéder à :',
    'fb_leads' => 'Des prospects d’affaires vérifiés',
    'fb_tools' => 'Des outils de vente automatisés',
    'fb_audits' => 'Des audits de croissance et un accompagnement conseil ?',
    'yes' => 'Oui, cela m’intéresse',
    'maybe' => 'Peut-être, envoyez-moi plus d’informations',
    'no' => 'Non, cela ne m’intéresse pas',
    'submit' => 'Envoyer',
    'done' => 'Terminé',
    'contact_info' => 'Coordonnées',
    'ph_name' => 'Nom complet',
    'ph_business' => 'Nom de l’entreprise',
    'ph_email' => 'Adresse e-mail',
    'ph_search_country' => 'Rechercher un pays...',
    'ph_code_first' => 'Choisissez d’abord l’indicatif',
    'ph_reg' => 'Saisissez le numéro d’immatriculation',
    'ph_search_countries' => 'Rechercher des pays...',
    'ph_website' => 'Saisissez le site ou les réseaux',
    'ph_profile' => 'Ajoutez l’URL de votre profil (facultatif)',
    'ph_year' => 'Saisissez l’année de création',
    'ph_value' => 'Saisissez votre valeur',
    'ph_desc' => 'Saisissez votre description',
    'ph_phone' => 'Saisissez le numéro',
    'no_results' => 'Aucun résultat',
    'err_name' => 'Le nom complet est obligatoire',
    'err_email' => 'L’adresse e-mail est obligatoire',
    'err_email_invalid' => 'Veuillez saisir une adresse e-mail valide',
    'err_phone' => 'Le numéro de téléphone est obligatoire',
    'err_title' => 'Veuillez choisir votre fonction',
    'err_company' => 'Le nom de l’entreprise est obligatoire',
    'err_reg' => 'Le numéro d’immatriculation est obligatoire',
    'err_country' => 'Le pays d’immatriculation est obligatoire',
    'err_website' => 'Le site ou le réseau social est obligatoire',
    'err_linkedin' => 'Veuillez saisir une URL LinkedIn valide',
    'err_year' => 'L’année de création est obligatoire',
    'thanks_title' => 'Vous venez de faire un grand pas vers une croissance concrète.',
    'thanks_body' => 'Consultez votre e-mail pour la suite et plus de détails de la part de NOEL.',
    'err_generic' => 'Une erreur s’est produite. Veuillez réessayer.',
    'err_session' => 'Votre session a expiré. Cliquez ci-dessous pour actualiser et réessayer.',
    'err_correct' => 'Veuillez corriger les erreurs ci-dessous et réessayer.',
    'err_denied' => 'Accès refusé. Actualisez la page et réessayez.',
    'err_rate' => 'Trop de tentatives. Patientez quelques minutes et réessayez.',
    'err_network' => 'Erreur réseau. Vérifiez votre connexion et réessayez.',
    'refresh' => 'Actualiser la page',
    'select_option' => 'Choisir une option',
    'ind_tech' => 'Technologie et services numériques',
    'ind_finance' => 'Services financiers et professionnels',
    'ind_mfg' => 'Industrie et fabrication',
    'ind_health' => 'Santé et pharmacie',
    'ind_energy' => 'Énergie et services publics',
    'ind_retail' => 'Commerce et biens de consommation',
    'ind_logistics' => 'Logistique et transport',
    'ind_agri' => 'Agriculture et agroalimentaire',
    'ind_property' => 'Immobilier et construction',
    'ind_media' => 'Médias et communication',
    'ind_edu' => 'Éducation et formation',
    'ind_gov' => 'Secteur public',
    'ind_env' => 'Environnement et services spécialisés',
"""

BRN_DE = r"""
    'meta' => 'Treten Sie einem exklusiven Empfehlungsnetzwerk für Führungskräfte bei, die vertrauensvolle Beziehungen und messbares Wachstum schätzen.',
    'meta_generator' => 'Treten Sie einem exklusiven Empfehlungsnetzwerk für Führungskräfte bei, die vertrauensvolle Beziehungen und messbares Wachstum schätzen | P23 Africa',
    'where' => 'Wo führende Geschäftsführer',
    'connect_grow' => 'sich vernetzen und wachsen',
    'intro' => 'Eine private, vertrauenswürdige Plattform, auf der geprüfte Geschäftsführer in Afrika Empfehlungen und strategische Geschäfte austauschen und Partnerschaften im direkten Gespräch aufbauen.',
    'register' => 'Jetzt registrieren',
    'how' => 'So arbeiten wir',
    'trial' => 'Bewerben Sie sich, werden Sie freigeschaltet und starten Sie Ihre 14-tägige kostenlose Testphase.',
    'connect_body' => 'Durchsuchen Sie das geprüfte Verzeichnis, werden Sie mit den passenden Fachleuten verbunden und teilen Sie Empfehlungen.',
    'collaborate_body' => 'Schreiben Sie Mitglieder direkt an und bauen Sie Ihr Netzwerk in einer exklusiven Gemeinschaft aus.',
    'stories' => 'Echte Erfolge von Geschäftsführern, die durch empfehlungsbasiertes Netzwerken gewachsen sind.',
    'role_partner' => 'Managing Partner.',
    'role_ceo' => 'Geschäftsführer.',
    'role_founder' => 'Gründer.',
    'role_director' => 'Direktorin.',
    'quote_linda' => 'Diese Gemeinschaft hat Türen geöffnet, an denen ich jahrelang gescheitert bin. Das Niveau der Fachleute sorgt dafür, dass aus jeder Empfehlung echtes Geschäft wird.',
    'quote_kofi' => 'Ich schätze, dass ich hier keine Zeit verliere. Das Matching hat mich mit Gründern in Ghana und darüber hinaus zusammengebracht, die wirklich zusammenarbeiten und wachsen wollen.',
    'quote_chijioke' => 'Was dieses Netzwerk auszeichnet, ist die Gemeinschaft. Alle sind qualifiziert, ernsthaft und offen für echte Chancen, nicht nur für Kontakte um der Kontakte willen.',
    'quote_zanele' => 'Endlich ein vertrauenswürdiges Netzwerk, das auf Qualität filtert. Jede Vorstellung ist bewusst, und ich habe bereits eine klare Rendite durch neue Partnerschaften gesehen.',
    'events_body' => 'Von geschlossenen Runden bis zu Business-Retreats ist jedes Ereignis auf echte Zusammenarbeit und Chancen ausgelegt.',
    'join_body' => 'Werden Sie Teil eines starken Ökosystems aus Empfehlungen, Beziehungen und Ergebnissen.',
    'register_interest' => 'Interesse anmelden',
    'start_basic' => 'Beginnen wir mit Ihren Grunddaten',
    'stand_out' => 'Heben Sie sich von anderen Unternehmen ab',
    'boost' => 'Mehr Sichtbarkeit für Ihre Kontakte',
    'intro_body' => 'NOEL macht aus gelegentlichen Kontakten unter Führungskräften strategisches Wachstum. Die Plattform ermöglicht geprüfte, hochwertige Vorstellungen zwischen Führungskräften und Investoren. Konzentrieren Sie sich auf Beziehungen, die echte Ergebnisse bringen.',
    'full_name' => 'Vollständiger Name',
    'title_label' => 'Funktion',
    'title_eg' => 'Funktion, z. B. CEO',
    'founder' => 'Gründer',
    'investor' => 'Investor',
    'md' => 'Geschäftsführer',
    'director' => 'Direktor',
    'manager' => 'Manager',
    'other' => 'Sonstiges',
    'business_name' => 'Offizieller Firmenname',
    'email' => 'E-Mail-Adresse',
    'email_ok' => 'E-Mail verfügbar',
    'validating' => 'Prüfung...',
    'phone' => 'Telefonnummer',
    'proceed' => 'Weiter',
    'reg_number' => 'Handelsregisternummer',
    'country_reg' => 'Land der Registrierung',
    'select_country' => 'Land auswählen',
    'website' => 'Website oder Social-Media-Profil',
    'linkedin' => 'LinkedIn-Profil',
    'no_linkedin' => 'Ich habe kein LinkedIn-Konto.',
    'year' => 'Gründungsjahr',
    'return' => 'Zurück',
    'industry_q' => 'In welcher Branche ist Ihr Unternehmen tätig?',
    'select_industry' => 'Branche auswählen',
    'employees' => 'Anzahl der Mitarbeitenden',
    'select_employees' => 'Mitarbeiterbereich wählen',
    'revenue' => 'Ungefährer Jahresumsatz',
    'confidential' => '(vertraulich – nur zur internen Verwendung)',
    'select_revenue' => 'Umsatzbereich wählen',
    'rev_1' => 'Unter 1 Mio. $',
    'rev_2' => '1 Mio. $ – 5 Mio. $',
    'rev_3' => 'Über 5 Mio. $',
    'offer_q' => 'Welchen Nutzen kann Ihr Unternehmen anderen Geschäftsführern im Netzwerk bieten?',
    'describe' => 'Beschreiben Sie kurz, was Ihr Unternehmen tut und wer Ihre Kunden sind (max. 150 Wörter)',
    'interest_q' => 'Was interessiert Sie an diesem Netzwerk am meisten?',
    'select_interest' => 'Interesse auswählen',
    'networking' => 'Netzwerk',
    'leads' => 'Geprüfte Geschäftskontakte',
    'tools' => 'Automatisierte Vertriebstools',
    'consulting' => 'Beratung',
    'fallback_q' => 'Wenn Ihr Unternehmen derzeit nicht für das Führungsnetzwerk infrage kommt: Möchten Sie unserer B2B-Plattform beitreten, um Zugang zu erhalten zu:',
    'fb_leads' => 'Geprüfte Geschäftskontakte',
    'fb_tools' => 'Automatisierte Vertriebstools',
    'fb_audits' => 'Wachstumsaudits und Beratung?',
    'yes' => 'Ja, ich bin interessiert',
    'maybe' => 'Vielleicht, senden Sie mir mehr Informationen',
    'no' => 'Nein, kein Interesse',
    'submit' => 'Senden',
    'done' => 'Fertig',
    'contact_info' => 'Kontakt',
    'ph_name' => 'Vollständiger Name',
    'ph_business' => 'Firmenname',
    'ph_email' => 'E-Mail-Adresse',
    'ph_search_country' => 'Land suchen...',
    'ph_code_first' => 'Zuerst die Vorwahl wählen',
    'ph_reg' => 'Registrierungsnummer eingeben',
    'ph_search_countries' => 'Länder suchen...',
    'ph_website' => 'Website oder Social-Media eingeben',
    'ph_profile' => 'Profil-URL hinzufügen (optional)',
    'ph_year' => 'Gründungsjahr eingeben',
    'ph_value' => 'Nutzen eingeben',
    'ph_desc' => 'Beschreibung eingeben',
    'ph_phone' => 'Telefonnummer eingeben',
    'no_results' => 'Keine Ergebnisse',
    'err_name' => 'Der vollständige Name ist erforderlich',
    'err_email' => 'Die E-Mail-Adresse ist erforderlich',
    'err_email_invalid' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein',
    'err_phone' => 'Die Telefonnummer ist erforderlich',
    'err_title' => 'Bitte wählen Sie Ihre Funktion',
    'err_company' => 'Der Firmenname ist erforderlich',
    'err_reg' => 'Die Registrierungsnummer ist erforderlich',
    'err_country' => 'Das Registrierungsland ist erforderlich',
    'err_website' => 'Website oder Social-Media-Profil ist erforderlich',
    'err_linkedin' => 'Bitte geben Sie eine gültige LinkedIn-URL ein',
    'err_year' => 'Das Gründungsjahr ist erforderlich',
    'thanks_title' => 'Sie haben einen großen Schritt zu echtem Geschäftswachstum gemacht.',
    'thanks_body' => 'Prüfen Sie Ihre E-Mail auf die nächsten Schritte und weitere Details von NOEL.',
    'err_generic' => 'Etwas ist schiefgelaufen. Bitte versuchen Sie es erneut.',
    'err_session' => 'Ihre Sitzung ist abgelaufen. Klicken Sie unten, um zu aktualisieren und es erneut zu versuchen.',
    'err_correct' => 'Bitte korrigieren Sie die Fehler unten und versuchen Sie es erneut.',
    'err_denied' => 'Zugriff verweigert. Bitte aktualisieren Sie die Seite und versuchen Sie es erneut.',
    'err_rate' => 'Zu viele Versuche. Bitte warten Sie einige Minuten und versuchen Sie es erneut.',
    'err_network' => 'Netzwerkfehler. Bitte prüfen Sie Ihre Verbindung und versuchen Sie es erneut.',
    'refresh' => 'Seite aktualisieren',
    'select_option' => 'Option wählen',
    'ind_tech' => 'Technologie und digitale Dienste',
    'ind_finance' => 'Finanz- und Beratungsdienste',
    'ind_mfg' => 'Fertigung und Industrie',
    'ind_health' => 'Gesundheit und Pharma',
    'ind_energy' => 'Energie und Versorgung',
    'ind_retail' => 'Handel und Konsumgüter',
    'ind_logistics' => 'Logistik und Transport',
    'ind_agri' => 'Landwirtschaft und Lebensmittel',
    'ind_property' => 'Immobilien und Bau',
    'ind_media' => 'Medien und Kommunikation',
    'ind_edu' => 'Bildung und Training',
    'ind_gov' => 'Öffentlicher Sektor',
    'ind_env' => 'Umwelt und Spezialdienste',
"""

BRN_NL = r"""
    'meta' => 'Word lid van een exclusief verwijzingsnetwerk voor leiders die vertrouwde relaties en meetbare groei belangrijk vinden.',
    'meta_generator' => 'Word lid van een exclusief verwijzingsnetwerk voor leiders die vertrouwde relaties en meetbare groei belangrijk vinden | P23 Africa',
    'where' => 'Waar toonaangevende CEO’s',
    'connect_grow' => 'elkaar vinden en groeien',
    'intro' => 'Een privé en betrouwbaar platform waar gescreende CEO’s in Afrika verwijzingen en strategische deals uitwisselen en partnerschappen opbouwen in directe gesprekken.',
    'register' => 'Nu registreren',
    'how' => 'Hoe wij werken',
    'trial' => 'Meld u aan, word goedgekeurd en start uw gratis proefperiode van 14 dagen.',
    'connect_body' => 'Blader door de gescreende gids, word gekoppeld aan de juiste professionals en deel verwijzingen.',
    'collaborate_body' => 'Stuur leden rechtstreeks een bericht en bouw uw netwerk uit in een exclusieve gemeenschap.',
    'stories' => 'Echte succesverhalen van CEO’s die zijn gegroeid door een netwerk op basis van verwijzingen.',
    'role_partner' => 'Managing partner.',
    'role_ceo' => 'CEO.',
    'role_founder' => 'Oprichter.',
    'role_director' => 'Directeur.',
    'quote_linda' => 'Deze gemeenschap heeft deuren geopend die ik jarenlang niet open kreeg. Het niveau van de professionals zorgt ervoor dat elke verwijzing echt zakelijk resultaat oplevert.',
    'quote_kofi' => 'Ik waardeer dat ik hier geen tijd verlies. De koppeling bracht me in contact met oprichters in Ghana en daarbuiten die echt willen samenwerken en groeien.',
    'quote_chijioke' => 'Wat dit onderscheidt, is de gemeenschap. Iedereen is gekwalificeerd, serieus en open voor echte kansen, niet alleen voor netwerken om het netwerken.',
    'quote_zanele' => 'Eindelijk een betrouwbaar netwerk dat op kwaliteit filtert. Elke introductie voelt bewust, en ik heb al een duidelijk rendement gezien via nieuwe partnerschappen.',
    'events_body' => 'Van besloten rondetafels tot zakelijke retraites: elk evenement is bedoeld voor echte samenwerking en kansen.',
    'join_body' => 'Sluit u aan bij een sterk ecosysteem van verwijzingen, relaties en resultaten.',
    'register_interest' => 'Interesse tonen',
    'start_basic' => 'Laten we beginnen met uw basisgegevens',
    'stand_out' => 'Onderscheid u van andere bedrijven',
    'boost' => 'Meer zichtbaarheid voor uw matches',
    'intro_body' => 'NOEL maakt van losse contacten tussen leiders strategische groei. Het platform faciliteert geverifieerde, waardevolle introducties tussen leiders en investeerders. Richt u op relaties die echte resultaten opleveren.',
    'full_name' => 'Volledige naam',
    'title_label' => 'Functie',
    'title_eg' => 'Functie, bijv. CEO',
    'founder' => 'Oprichter',
    'investor' => 'Investeerder',
    'md' => 'Algemeen directeur',
    'director' => 'Directeur',
    'manager' => 'Manager',
    'other' => 'Overig',
    'business_name' => 'Officiële bedrijfsnaam',
    'email' => 'E-mailadres',
    'email_ok' => 'E-mail beschikbaar',
    'validating' => 'Controleren...',
    'phone' => 'Telefoonnummer',
    'proceed' => 'Verder',
    'reg_number' => 'Registratienummer',
    'country_reg' => 'Land van registratie',
    'select_country' => 'Kies uw land',
    'website' => 'Website of socialmedia-account',
    'linkedin' => 'LinkedIn-profiel',
    'no_linkedin' => 'Ik heb geen LinkedIn-account.',
    'year' => 'Oprichtingsjaar',
    'return' => 'Terug',
    'industry_q' => 'In welke sector is uw bedrijf actief?',
    'select_industry' => 'Kies uw sector',
    'employees' => 'Aantal medewerkers',
    'select_employees' => 'Kies een personeelsbereik',
    'revenue' => 'Geschatte jaaromzet',
    'confidential' => '(vertrouwelijk – alleen voor intern gebruik)',
    'select_revenue' => 'Kies een omzetbereik',
    'rev_1' => 'Onder $1 mln',
    'rev_2' => '$1 mln – $5 mln',
    'rev_3' => '$5 mln+',
    'offer_q' => 'Welke waarde kan uw bedrijf andere CEO’s in het netwerk bieden?',
    'describe' => 'Beschrijf kort wat uw bedrijf doet en wie uw klanten zijn (max. 150 woorden)',
    'interest_q' => 'Wat interesseert u het meest aan dit netwerk?',
    'select_interest' => 'Kies een interesse',
    'networking' => 'Netwerken',
    'leads' => 'Geverifieerde zakelijke leads',
    'tools' => 'Geautomatiseerde verkooptools',
    'consulting' => 'Adviesondersteuning',
    'fallback_q' => 'Als uw bedrijf nu niet in aanmerking komt voor het CEO-netwerk, wilt u dan ons B2B-platform gebruiken voor toegang tot:',
    'fb_leads' => 'Geverifieerde zakelijke leads',
    'fb_tools' => 'Geautomatiseerde verkooptools',
    'fb_audits' => 'Groeiaudits en advies?',
    'yes' => 'Ja, ik heb interesse',
    'maybe' => 'Misschien, stuur mij meer informatie',
    'no' => 'Nee, geen interesse',
    'submit' => 'Versturen',
    'done' => 'Klaar',
    'contact_info' => 'Contactgegevens',
    'ph_name' => 'Volledige naam',
    'ph_business' => 'Bedrijfsnaam',
    'ph_email' => 'E-mailadres',
    'ph_search_country' => 'Land zoeken...',
    'ph_code_first' => 'Kies eerst de landcode',
    'ph_reg' => 'Vul het registratienummer in',
    'ph_search_countries' => 'Landen zoeken...',
    'ph_website' => 'Vul website of socials in',
    'ph_profile' => 'Voeg uw profiel-URL toe (optioneel)',
    'ph_year' => 'Vul het oprichtingsjaar in',
    'ph_value' => 'Vul uw waarde in',
    'ph_desc' => 'Vul uw beschrijving in',
    'ph_phone' => 'Vul het telefoonnummer in',
    'no_results' => 'Geen resultaten',
    'err_name' => 'Volledige naam is verplicht',
    'err_email' => 'E-mailadres is verplicht',
    'err_email_invalid' => 'Vul een geldig e-mailadres in',
    'err_phone' => 'Telefoonnummer is verplicht',
    'err_title' => 'Kies uw functie',
    'err_company' => 'Bedrijfsnaam is verplicht',
    'err_reg' => 'Registratienummer is verplicht',
    'err_country' => 'Land van registratie is verplicht',
    'err_website' => 'Website of socialmedia-account is verplicht',
    'err_linkedin' => 'Vul een geldige LinkedIn-URL in',
    'err_year' => 'Oprichtingsjaar is verplicht',
    'thanks_title' => 'U heeft een grote stap gezet naar betekenisvolle groei.',
    'thanks_body' => 'Controleer uw e-mail voor de volgende stappen en meer details van NOEL.',
    'err_generic' => 'Er ging iets mis. Probeer het opnieuw.',
    'err_session' => 'Uw sessie is verlopen. Klik hieronder om te vernieuwen en het opnieuw te proberen.',
    'err_correct' => 'Corrigeer de fouten hieronder en probeer het opnieuw.',
    'err_denied' => 'Toegang geweigerd. Vernieuw de pagina en probeer het opnieuw.',
    'err_rate' => 'Te veel pogingen. Wacht een paar minuten en probeer het opnieuw.',
    'err_network' => 'Netwerkfout. Controleer uw verbinding en probeer het opnieuw.',
    'refresh' => 'Pagina vernieuwen',
    'select_option' => 'Optie kiezen',
    'ind_tech' => 'Technologie en digitale diensten',
    'ind_finance' => 'Financiële en professionele diensten',
    'ind_mfg' => 'Maakindustrie',
    'ind_health' => 'Zorg en farma',
    'ind_energy' => 'Energie en nutsbedrijven',
    'ind_retail' => 'Retail en consumentengoederen',
    'ind_logistics' => 'Logistiek en transport',
    'ind_agri' => 'Landbouw en voedselverwerking',
    'ind_property' => 'Vastgoed en bouw',
    'ind_media' => 'Media en communicatie',
    'ind_edu' => 'Onderwijs en training',
    'ind_gov' => 'Overheid en publieke diensten',
    'ind_env' => 'Milieu en gespecialiseerde diensten',
"""

CONSULT = {
    "en": r"""
    'clarity' => 'Let’s help you unlock growth clarity for your business.',
    'get_started' => 'Let’s get started',
    'analyze' => 'The consultation will analyze your challenges, identify growth blockers, and design a roadmap to help you scale sustainably in African markets.',
""",
    "fr": r"""
    'clarity' => 'Nous vous aidons à clarifier la croissance de votre entreprise.',
    'get_started' => 'Commençons',
    'analyze' => 'La consultation analysera vos défis, identifiera les freins à la croissance et concevra une feuille de route pour grandir durablement sur les marchés africains.',
""",
    "de": r"""
    'clarity' => 'Wir helfen Ihnen, Klarheit über das Wachstum Ihres Unternehmens zu gewinnen.',
    'get_started' => 'Legen wir los',
    'analyze' => 'Die Beratung analysiert Ihre Herausforderungen, erkennt Wachstumsbremsen und entwirft einen Fahrplan, damit Sie auf afrikanischen Märkten nachhaltig wachsen.',
""",
    "nl": r"""
    'clarity' => 'Wij helpen u helderheid te krijgen over de groei van uw bedrijf.',
    'get_started' => 'Laten we beginnen',
    'analyze' => 'Het gesprek analyseert uw uitdagingen, vindt groeiblokkades en ontwerpt een routekaart om duurzaam te groeien op Afrikaanse markten.',
""",
}

PLAY = {
    "en": r"""
    'subtitle' => 'Your comprehensive guide to building and scaling successful businesses across Africa. Get expert insights, proven strategies, and actionable advice from successful entrepreneurs.',
    'download' => 'Download Free Playbook',
    'sending' => 'Sending...',
    'instant' => 'Instant Download',
    'no_spam' => 'No Spam',
    'brand' => 'P23 Africa’s',
    'founders' => 'FOUNDERS',
    'playbook_word' => 'PLAYBOOK',
    'cover' => 'Your Complete Guide to Building & Scaling Successful Businesses in Africa',
    'inbox' => 'Enter your details to receive the P23 Africa’s Founders Playbook directly in your inbox.',
    'full_name' => 'Full Name *',
    'email' => 'Email Address *',
    'company' => 'Company Name *',
    'get_mine' => 'Get My Playbook',
    'submitting' => 'Submitting...',
    'name_required' => 'Full name is required',
    'email_required' => 'Email address is required',
    'email_invalid' => 'Please enter a valid email address',
    'company_required' => 'Company name is required',
    'sent_title' => 'Playbook Sent Successfully!',
    'sent_body' => 'The P23 Africa’s Founders Playbook has been sent to your email. Please check your inbox.',
    'fail_title' => 'Submission Failed',
    'fail_body' => 'An unexpected error occurred. Please try again.',
    'slow_title' => 'Request Taking Longer Than Expected',
    'slow_body' => 'Your playbook request is still being processed. Please check your email in a few minutes.',
    'sent_btn' => 'Playbook Sent! Check Your Email',
    'try_again' => 'Try Again',
""",
    "fr": r"""
    'subtitle' => 'Votre guide complet pour créer et développer des entreprises qui réussissent en Afrique. Des éclairages d’experts, des stratégies éprouvées et des conseils concrets d’entrepreneurs.',
    'download' => 'Télécharger le guide gratuit',
    'sending' => 'Envoi...',
    'instant' => 'Téléchargement immédiat',
    'no_spam' => 'Pas de spam',
    'brand' => 'P23 Africa',
    'founders' => 'FONDATEURS',
    'playbook_word' => 'GUIDE',
    'cover' => 'Votre guide complet pour créer et développer des entreprises qui réussissent en Afrique',
    'inbox' => 'Saisissez vos coordonnées pour recevoir le guide des fondateurs de P23 Africa dans votre boîte mail.',
    'full_name' => 'Nom complet *',
    'email' => 'Adresse e-mail *',
    'company' => 'Nom de l’entreprise *',
    'get_mine' => 'Recevoir mon guide',
    'submitting' => 'Envoi...',
    'name_required' => 'Le nom complet est obligatoire',
    'email_required' => 'L’adresse e-mail est obligatoire',
    'email_invalid' => 'Veuillez saisir une adresse e-mail valide',
    'company_required' => 'Le nom de l’entreprise est obligatoire',
    'sent_title' => 'Guide envoyé',
    'sent_body' => 'Le guide des fondateurs de P23 Africa a été envoyé à votre adresse e-mail. Consultez votre boîte de réception.',
    'fail_title' => 'Échec de l’envoi',
    'fail_body' => 'Une erreur inattendue s’est produite. Veuillez réessayer.',
    'slow_title' => 'La demande prend plus de temps que prévu',
    'slow_body' => 'Votre demande est encore en cours. Consultez votre e-mail dans quelques minutes.',
    'sent_btn' => 'Guide envoyé ! Consultez votre e-mail',
    'try_again' => 'Réessayer',
""",
    "de": r"""
    'subtitle' => 'Ihr umfassender Leitfaden zum Aufbau und zur Skalierung erfolgreicher Unternehmen in Afrika. Fachwissen, bewährte Strategien und umsetzbare Ratschläge von Unternehmern.',
    'download' => 'Kostenlosen Leitfaden herunterladen',
    'sending' => 'Wird gesendet...',
    'instant' => 'Sofort-Download',
    'no_spam' => 'Kein Spam',
    'brand' => 'P23 Africa',
    'founders' => 'GRÜNDER',
    'playbook_word' => 'LEITFADEN',
    'cover' => 'Ihr vollständiger Leitfaden zum Aufbau und zur Skalierung erfolgreicher Unternehmen in Afrika',
    'inbox' => 'Geben Sie Ihre Daten ein, um den Gründerleitfaden von P23 Africa direkt per E-Mail zu erhalten.',
    'full_name' => 'Vollständiger Name *',
    'email' => 'E-Mail-Adresse *',
    'company' => 'Firmenname *',
    'get_mine' => 'Meinen Leitfaden erhalten',
    'submitting' => 'Wird gesendet...',
    'name_required' => 'Der vollständige Name ist erforderlich',
    'email_required' => 'Die E-Mail-Adresse ist erforderlich',
    'email_invalid' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein',
    'company_required' => 'Der Firmenname ist erforderlich',
    'sent_title' => 'Leitfaden gesendet',
    'sent_body' => 'Der Gründerleitfaden von P23 Africa wurde an Ihre E-Mail gesendet. Bitte prüfen Sie Ihren Posteingang.',
    'fail_title' => 'Senden fehlgeschlagen',
    'fail_body' => 'Ein unerwarteter Fehler ist aufgetreten. Bitte versuchen Sie es erneut.',
    'slow_title' => 'Die Anfrage dauert länger als erwartet',
    'slow_body' => 'Ihre Anfrage wird noch bearbeitet. Bitte prüfen Sie Ihre E-Mail in einigen Minuten.',
    'sent_btn' => 'Leitfaden gesendet! E-Mail prüfen',
    'try_again' => 'Erneut versuchen',
""",
    "nl": r"""
    'subtitle' => 'Uw complete gids voor het bouwen en opschalen van succesvolle bedrijven in Afrika. Inzichten van experts, bewezen strategieën en praktisch advies van ondernemers.',
    'download' => 'Gratis gids downloaden',
    'sending' => 'Verzenden...',
    'instant' => 'Directe download',
    'no_spam' => 'Geen spam',
    'brand' => 'P23 Africa',
    'founders' => 'OPRICHTERS',
    'playbook_word' => 'GIDS',
    'cover' => 'Uw complete gids voor het bouwen en opschalen van succesvolle bedrijven in Afrika',
    'inbox' => 'Vul uw gegevens in om de oprichtersgids van P23 Africa in uw inbox te ontvangen.',
    'full_name' => 'Volledige naam *',
    'email' => 'E-mailadres *',
    'company' => 'Bedrijfsnaam *',
    'get_mine' => 'Mijn gids ontvangen',
    'submitting' => 'Verzenden...',
    'name_required' => 'Volledige naam is verplicht',
    'email_required' => 'E-mailadres is verplicht',
    'email_invalid' => 'Vul een geldig e-mailadres in',
    'company_required' => 'Bedrijfsnaam is verplicht',
    'sent_title' => 'Gids verzonden',
    'sent_body' => 'De oprichtersgids van P23 Africa is naar uw e-mail gestuurd. Controleer uw inbox.',
    'fail_title' => 'Verzenden mislukt',
    'fail_body' => 'Er is een onverwachte fout opgetreden. Probeer het opnieuw.',
    'slow_title' => 'Het verzoek duurt langer dan verwacht',
    'slow_body' => 'Uw verzoek wordt nog verwerkt. Controleer uw e-mail over een paar minuten.',
    'sent_btn' => 'Gids verzonden! Controleer uw e-mail',
    'try_again' => 'Opnieuw proberen',
""",
}


def wire_sales(text):
    text = sub(text, "@section('title', 'Sales | P23 Africa')", "@section('title', __('sales_page.title'))", "sales title")
    text = sub(
        text,
        'content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!"',
        'content="{{ __(\'sales_page.meta\') }}"',
        "sales meta",
    )
    text = sub(
        text,
        'content="Business Consulting & Growth Solutions for Africa | P23 Africa"',
        'content="{{ __(\'sales_page.meta_generator\') }}"',
        "sales generator",
    )
    text = sub(text, "Stop Losing <span>Leads.</span>", "{{ __('sales_page.stop') }} <span>{{ __('sales_page.leads') }}</span>", "sales stop")
    text = sub(text, "Start Closing <span>More Deals.</span>", "{{ __('sales_page.start_closing') }} <span>{{ __('sales_page.deals') }}</span>", "sales close")
    text = rsub(
        text,
        r"<strong>Sales Growth Toolkit for African Businesses</strong>\s*– Train your team\. Track leads\. Close more deals\.",
        "<strong>{{ __('sales_page.toolkit') }}</strong>\n                                    {{ __('sales_page.toolkit_line') }}",
        "sales toolkit",
    )
    text = sub(text, "<p class=\"text-uppercase\">Countries</p>", "<p class=\"text-uppercase\">{{ __('sales_page.countries') }}</p>", "countries")
    text = sub(text, "<p class=\"text-uppercase\">Businesses</p>", "<p class=\"text-uppercase\">{{ __('sales_page.businesses') }}</p>", "businesses")
    text = sub(text, "<p class=\"text-uppercase\">Partnership</p>", "<p class=\"text-uppercase\">{{ __('sales_page.partnership') }}</p>", "partnership")
    text = sub(text, 'alt="Sales Growth Video"', 'alt="{{ __(\'sales_page.alt_video\') }}"', "alt video")
    text = sub(text, 'aria-label="Play video"', 'aria-label="{{ __(\'sales_page.play\') }}"', "play")
    text = sub(text, 'aria-label="Close"', 'aria-label="{{ __(\'sales_page.close\') }}"', "close")
    text = sub(text, "<p class=\"text-uppercase mb-2\">How It Works</p>", "<p class=\"text-uppercase mb-2\">{{ __('sales_page.how') }}</p>", "how")
    text = sub(text, "<h2 class=\"\">Simple, Trusted & Effective</h2>", "<h2 class=\"\">{{ __('sales_page.simple') }}</h2>", "simple")
    text = sub(text, 'alt="Work Card"', 'alt="{{ __(\'sales_page.alt_card\') }}"', "work card")
    text = sub(text, "<h1>START WITH A FREE BUSINESS AUDIT</h1>", "<h1>{{ __('sales_page.audit_title') }}</h1>", "audit title")
    text = rsub(
        text,
        r"Let's show you what's leaking.*?</p>",
        "{{ __('sales_page.audit_body') }}\n                            </p>",
        "audit body",
    )
    text = sub(text, "Yes, I Want My Free Audit", "{{ __('sales_page.audit_cta') }}", "audit cta")
    text = sub(text, 'alt="Business team collaborating"', 'alt="{{ __(\'sales_page.alt_team\') }}"', "team alt")
    text = sub(text, "<h2>This offer is perfect for</h2>", "<h2>{{ __('sales_page.offer') }}</h2>", "offer")
    text = sub(text, 'alt="Offer"', 'alt="{{ __(\'sales_page.alt_offer\') }}"', "offer alt")
    text = sub(text, "<h2>Our Happy Clients Say</h2>", "<h2>{{ __('sales_page.clients') }}</h2>", "clients")
    text = sub(text, 'alt="Testimonial"', 'alt="{{ __(\'sales_page.alt_testimonial\') }}"', "testimonial alt")
    text = rsub(text, r'"Before this, our sales process.*?"', "{{ __('sales_page.quote_juliet') }}", "juliet")
    text = rsub(text, r'"As someone with seven years.*?"', "{{ __('sales_page.quote_james') }}", "james")
    text = rsub(text, r'"I took this leap of faith.*?"', "{{ __('sales_page.quote_vera') }}", "vera")
    text = sub(text, "<p>Sales Manager</p>", "<p>{{ __('sales_page.role_juliet') }}</p>", "role juliet")
    text = sub(text, "<p>Marketing Manager</p>", "<p>{{ __('sales_page.role_james') }}</p>", "role james")
    text = sub(text, "<p>Team Lead</p>", "<p>{{ __('sales_page.role_vera') }}</p>", "role vera")
    text = sub(text, "<h2>Trained <strong>3000+ Teams</strong></h2>", "<h2>{{ __('sales_page.trained') }} <strong>{{ __('sales_page.teams') }}</strong></h2>", "trained")
    text = sub(text, 'alt="Business People"', 'alt="{{ __(\'sales_page.alt_people\') }}"', "people")
    text = sub(text, "<h2>Packages</h2>", "<h2>{{ __('sales_page.packages') }}</h2>", "packages")
    text = sub(text, "<strong>Standard</strong>", "<strong>{{ __('sales_page.standard') }}</strong>", "standard")
    text = rsub(
        text,
        r"Perfect for startups and small teams looking to build a basic but\s*effective\s*sales system\.",
        "{{ __('sales_page.standard_body') }}",
        "std body",
    )
    text = sub(text, "<b>Includes:</b>", "<b>{{ __('sales_page.includes') }}</b>", "includes")
    text = sub(text, "Half-day Sales Training Sales script templates (email, call, DM)", "{{ __('sales_page.std_1') }}", "std1")
    text = sub(text, "Sales tracking spreadsheet (editable)", "{{ __('sales_page.std_2') }}", "std2")
    text = sub(text, "Lead qualification checklist", "{{ __('sales_page.std_3') }}", "std3")
    text = sub(text, "30-minute onboarding session", "{{ __('sales_page.std_4') }}", "std4")
    text = sub(text, "Email support (7 days post-purchase)", "{{ __('sales_page.std_5') }}", "std5")
    text = sub(text, "<small>/one-time</small>", "<small>{{ __('sales_page.one_time') }}</small>", "one time")
    text = sub(text, '<a href="#" class="btn w-100">Standard</a>', '<a href="#" class="btn w-100">{{ __(\'sales_page.standard\') }}</a>', "std btn")
    text = sub(text, "<strong>Pro</strong>", "<strong>{{ __('sales_page.pro') }}</strong>", "pro")
    text = rsub(
        text,
        r"For businesses ready to optimise and scale their sales process\.",
        "{{ __('sales_page.pro_body') }}",
        "pro body",
    )
    text = rsub(
        text,
        r"Full-day sales training Customised sales playbook tailored to\s*your\s*business",
        "{{ __('sales_page.pro_1') }}",
        "pro1",
    )
    text = sub(text, "Objection-handling guide", "{{ __('sales_page.pro_2') }}", "pro2")
    text = sub(text, "1x 60-minute strategy call", "{{ __('sales_page.pro_3') }}", "pro3")
    text = sub(text, "Sales process audit + recommendations", "{{ __('sales_page.pro_4') }}", "pro4")
    text = sub(text, "30 days of email + WhatsApp support", "{{ __('sales_page.pro_5') }}", "pro5")
    text = sub(text, "Bonus: Lead pipeline tracker (advanced version)", "{{ __('sales_page.pro_6') }}", "pro6")
    text = sub(text, '<a href="#" class="btn w-100">Pro</a>', '<a href="#" class="btn w-100">{{ __(\'sales_page.pro\') }}</a>', "pro btn")
    text = sub(text, "<h2>Ready To Turn Leads Into Revenue?</h2>", "<h2>{{ __('sales_page.ready') }}</h2>", "ready")
    text = rsub(
        text,
        r"No guesswork\. No CRM overwhelm\. Just real systems that deliver\s*results\.",
        "{{ __('sales_page.ready_body') }}",
        "ready body",
    )
    text = sub(text, "Claim My Free Sales Audit!", "{{ __('sales_page.claim') }}", "claim")
    return text


def wire_quiz_pages(text, which):
    if which == "sales":
        text = sub(text, "@section('title', 'Quiz | P23 Africa')", "@section('title', __('quiz.quiz_title'))", "quiz title")
        text = sub(
            text,
            'content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!"',
            'content="{{ __(\'quiz.meta\') }}"',
            "quiz meta",
        )
        text = sub(
            text,
            'content="Business Consulting & Growth Solutions for Africa | P23 Africa"',
            'content="{{ __(\'quiz.meta_generator\') }}"',
            "quiz gen",
        )
        text = sub(text, "{{ route('homepage') }}", "{{ locale_route('homepage') }}", "home route")
        text = sub(text, "{{ route('sales') }}", "{{ locale_route('sales') }}", "sales route")
        text = sub(text, "{{ route('quiz') }}", "{{ locale_route('quiz') }}", "quiz route")
        text = sub(text, "{{ route('start-quiz') }}", "{{ locale_route('start-quiz') }}", "start route")
        text = sub(text, 'aria-label="Toggle navigation"', 'aria-label="{{ __(\'quiz.toggle_nav\') }}"', "toggle")
        text = sub(text, "<span class=\"nav-main-text\">Home</span>", "<span class=\"nav-main-text\">{{ __('quiz.home') }}</span>", "nav home")
        text = sub(text, ">How It Works</a>", ">{{ __('quiz.how') }}</a>", "nav how")
        text = sub(text, ">Quiz</a>", ">{{ __('quiz.quiz') }}</a>", "nav quiz")
        text = rsub(
            text,
            r"Take this 2-minute quiz to find out what's costing your business\s*money—and how to fix it\.",
            "{{ __('quiz.intro') }}",
            "intro",
        )
        text = sub(text, "Start Quiz Now!", "{{ __('quiz.start_now') }}", "start now")
        text = rsub(
            text,
            r"You might be losing sales and opportunities without even\s*realizing it\. Answer these 8 quick questions to discover\s*hidden gaps in your sales, team structure, and operations\.",
            "{{ __('quiz.hint') }}",
            "hint",
        )
        text = sub(text, 'alt="Business Professionals"', 'alt="{{ __(\'quiz.alt_people\') }}"', "people")
    elif which == "start":
        text = sub(text, "@section('title', 'Start Quiz | P23 Africa')", "@section('title', __('quiz.start_title'))", "start title")
        text = sub(
            text,
            'content="Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!"',
            'content="{{ __(\'quiz.meta\') }}"',
            "start meta",
        )
        text = sub(
            text,
            'content="Business Consulting & Growth Solutions for Africa | P23 Africa"',
            'content="{{ __(\'quiz.meta_generator\') }}"',
            "start gen",
        )
        text = sub(text, "<h4>Almost There! Enter your details to see your result.</h4>", "<h4>{{ __('quiz.almost') }}</h4>", "almost")
        text = sub(text, 'placeholder="Full name"', 'placeholder="{{ __(\'quiz.full_name\') }}"', "name ph")
        text = sub(text, 'placeholder="Your Email"', 'placeholder="{{ __(\'quiz.email\') }}"', "email ph")
        text = sub(text, "Submit & See Results", "{{ __('quiz.submit_results') }}", "submit results")
        text = sub(text, '<span class="progress-text" id="progressText">1 of 8</span>', '<span class="progress-text" id="progressText">{{ __(\'quiz.progress\', [\'current\' => 1, \'total\' => 8]) }}</span>', "progress")
    return text


def wire_quiz_js(text):
    text = rsub(
        text,
        r"const questions = \[\{.*?\n    \];",
        """const questions = @json(array_map(function ($item) {
        return [
            'question' => $item['question'],
            'options' => ['A' => $item['a'], 'B' => $item['b'], 'C' => $item['c']],
        ];
    }, __('quiz.questions')));""",
        "questions array",
    )
    text = sub(
        text,
        'document.getElementById("progressText").innerText = `${currentQuestion + 1} of ${questions.length}`;',
        'document.getElementById("progressText").innerText = @json(__(\'quiz.progress\')).replace(\':current\', currentQuestion + 1).replace(\':total\', questions.length);',
        "progress js",
    )
    text = sub(text, '<span class="challenge-label">Challenge 1:</span>', '<span class="challenge-label">${@json(__(\'quiz.ch1\'))}</span>', "ch1")
    text = sub(text, '<span class="challenge-label">Challenge 2:</span>', '<span class="challenge-label">${@json(__(\'quiz.ch2\'))}</span>', "ch2")
    text = sub(text, '<span class="challenge-label">Challenge 3:</span>', '<span class="challenge-label">${@json(__(\'quiz.ch3\'))}</span>', "ch3")
    text = sub(text, 'placeholder="Type in your first challenge here"', 'placeholder="${@json(__(\'quiz.ch1_ph\'))}"', "ph1")
    text = sub(text, 'placeholder="Type in your second challenge here"', 'placeholder="${@json(__(\'quiz.ch2_ph\'))}"', "ph2")
    text = sub(text, 'placeholder="Type in your third challenge here"', 'placeholder="${@json(__(\'quiz.ch3_ph\'))}"', "ph3")
    text = sub(text, 'nextBtn.textContent = "See Results →";', 'nextBtn.textContent = @json(__(\'quiz.see_results\'));', "see")
    text = sub(text, 'this.textContent = "Continue →";', 'this.textContent = @json(__(\'quiz.continue_btn\'));', "continue")
    text = sub(text, 'alert("Please enter a valid email address.");', 'alert(@json(__(\'quiz.invalid_email\')));', "email alert")
    text = sub(text, 'alert("Please enter your full name.");', 'alert(@json(__(\'quiz.invalid_name\')));', "name alert")
    text = sub(text, "Analysing...", "{{ __('quiz.analysing') }}", "analysing")
    return text


def wire_result(text, prefix, title_en, sub_en, name_en):
    text = sub(text, f"@section('title', '{title_en}')", f"@section('title', __('quiz.{prefix}_title'))", f"{prefix} title")
    text = sub(text, f'content="{sub_en}"', "content=\"{{ __('quiz." + prefix + "_sub') }}\"", f"{prefix} meta")
    text = sub(
        text,
        'content="Business Consulting & Growth Solutions for Africa | P23 Africa"',
        'content="{{ __(\'quiz.meta_generator\') }}"',
        f"{prefix} gen",
    )
    text = sub(text, "<span>YOU ARE THE</span>", "<span>{{ __('quiz.you_are') }}</span>", f"{prefix} you")
    text = sub(text, f'const title = "{name_en}";', f"const title = @json(__('quiz.{prefix}_name'));", f"{prefix} js title")
    text = rsub(
        text,
        r"const subtitle =\s*\n\s*\".*?\";",
        f"const subtitle = @json(__('quiz.{prefix}_sub'));",
        f"{prefix} js sub",
    )
    text = sub(text, "Book Business Audit Now!", "{{ __('quiz.book_audit') }}", f"{prefix} book")
    text = sub(text, "Get Growth Tips", "{{ __('quiz.tips') }}", f"{prefix} tips")
    text = sub(text, 'aria-label="Close"', 'aria-label="{{ __(\'quiz.close\') }}"', f"{prefix} close")
    return text


def wire_events(text, kind):
    if kind == "all":
        text = sub(text, 'el.innerHTML = "Event Started";', "el.innerHTML = @json(__('events.event_started'));", "started")
        text = sub(
            text,
            "el.innerHTML = `Happening in ${hours}h ${minutes}m`;",
            "el.innerHTML = @json(__('events.happening_in')).replace(':hours', hours).replace(':minutes', minutes);",
            "happening",
        )
    elif kind == "show":
        text = sub(text, "<h6 class=\"text-uppercase fw-bold\">About This Event</h6>", "<h6 class=\"text-uppercase fw-bold\">{{ __('events.about') }}</h6>", "about")
        text = sub(text, "<strong>1hr 30mins</strong>", "<strong>{{ __('events.duration') }}</strong>", "duration")
        text = sub(text, "?? 'Google Meet'", "?? __('events.google_meet')", "meet")
        text = rsub(text, r"Book A\s*Seat", "{{ __('events.book_seat') }}", "book seat")
        text = sub(text, "Share This Event", "{{ __('events.share') }}", "share")
        text = sub(text, "Edit Event", "{{ __('events.edit') }}", "edit")
        text = sub(text, "Link copied to clipboard!", "{{ __('events.copied') }}", "copied")
        text = sub(text, "<h5 class=\"text-center mb-3\">Enter Your Seat Code</h5>", "<h5 class=\"text-center mb-3\">{{ __('events.seat_code_title') }}</h5>", "seat title")
        text = sub(text, 'placeholder="Enter your seat code"', 'placeholder="{{ __(\'events.seat_code_ph\') }}"', "seat ph")
        text = sub(text, ">Join Event</button>", ">{{ __('events.join') }}</button>", "join")
    elif kind == "live":
        text = sub(text, "<h2 class=\"mb-4\">Welcome {{ $seat->name }}</h2>", "<h2 class=\"mb-4\">{{ __('events.welcome', ['name' => $seat->name]) }}</h2>", "welcome")
        text = sub(text, "<p class=\"text-muted mb-5\">You’ve joined the live session!</p>", "<p class=\"text-muted mb-5\">{{ __('events.joined') }}</p>", "joined")
        text = sub(text, "<strong>Date:</strong>", "<strong>{{ __('events.date') }}</strong>", "date")
        text = sub(text, "<strong>Time:</strong>", "<strong>{{ __('events.time') }}</strong>", "time")
        text = sub(text, 'alt="Speaker"', 'alt="{{ __(\'events.alt_speaker\') }}"', "speaker")
    elif kind == "book":
        text = rsub(
            text,
            r"Book\s*your seat to be a part of this exclusive event and gain valuable insights from trusted\s*experts\.",
            "{{ __('events.book_intro') }}",
            "book intro",
        )
        text = sub(text, "Submit <i class=\"bi bi-arrow-right ms-2\"></i>", "{{ __('events.submit') }} <i class=\"bi bi-arrow-right ms-2\"></i>", "submit")
        text = rsub(
            text,
            r"Thank you for signing up\. We've sent event details to your email <br>\s*<span class=\"\">— please check your inbox\.</span>",
            "{{ __('events.signup_thanks') }} <br>\n                <span class=\"\">{{ __('events.check_inbox') }}</span>",
            "thanks",
        )
        text = sub(text, "View Other Events", "{{ __('events.view_other') }}", "view other")
        text = sub(text, 'aria-label="Close"', 'aria-label="{{ __(\'events.close\') }}"', "close")
    elif kind == "feedback":
        text = sub(text, "<label>How would you rate this event?</label>", "<label>{{ __('events.rate') }}</label>", "rate")
        text = sub(text, "> Excellent</label>", "> {{ __('events.excellent') }}</label>", "excellent")
        text = sub(text, "> Good</label>", "> {{ __('events.good') }}</label>", "good")
        text = sub(text, "> Fair</label>", "> {{ __('events.fair') }}</label>", "fair")
        text = sub(text, "> Poor</label>", "> {{ __('events.poor') }}</label>", "poor")
        text = sub(text, "<label>Comments</label>", "<label>{{ __('events.comments') }}</label>", "comments")
        text = sub(text, ">Submit Feedback</button>", ">{{ __('events.submit_feedback') }}</button>", "submit fb")
    return text


def wire_brn_page(text):
    text = sub(text, "@section('title', 'NOELHQ | P23 Africa')", "@section('title', 'NOELHQ | P23 Africa')", "title keep")
    text = sub(
        text,
        'content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth."',
        'content="{{ __(\'brn.meta\') }}"',
        "brn meta",
    )
    text = sub(
        text,
        'content="Join an exclusive referral network designed for industry leaders who value trusted relationships and measurable growth | P23 Africa"',
        'content="{{ __(\'brn.meta_generator\') }}"',
        "brn gen",
    )
    text = sub(text, "Where Top CEOs <br />", "{{ __('brn.where') }} <br />", "where")
    text = sub(text, "<span class=\"d-block\">Connect and Grow</span>", "<span class=\"d-block\">{{ __('brn.connect_grow') }}</span>", "connect grow")
    text = rsub(
        text,
        r"A private, trusted platform for vetted CEOs across Africa to exchange referrals, strategic\s*deals, and build partnerships through direct 1:1 connections\.",
        "{{ __('brn.intro') }}",
        "intro",
    )
    text = sub(text, "{{ route('brn-form') }}", "{{ locale_route('brn-form') }}", "form route")
    text = sub(text, "Register Now!", "{{ __('brn.register') }}", "register")
    text = sub(text, "<h4>How We Operate</h4>", "<h4>{{ __('brn.how') }}</h4>", "how")
    text = rsub(
        text,
        r"Apply, get approved, and start <span class=\"d-md-inline d-block\">your 14-day free\s*trial\.</span>",
        "{{ __('brn.trial') }}",
        "trial",
    )
    text = rsub(
        text,
        r"Browse the vetted directory, get matched with the right professionals, and share referrals\.",
        "{{ __('brn.connect_body') }}",
        "connect body",
    )
    text = rsub(
        text,
        r"Message members directly and grow your network inside an exclusive community\.",
        "{{ __('brn.collaborate_body') }}",
        "collab",
    )
    text = rsub(
        text,
        r"Real success stories from CEOs who’ve grown through the power of referral-based\s*networking\.",
        "{{ __('brn.stories') }}",
        "stories",
    )
    text = sub(text, "Linda Mwangi, Managing Partner.", "Linda Mwangi, {{ __('brn.role_partner') }}", "linda role")
    text = rsub(text, r"This community has opened doors.*?</p>", "{{ __('brn.quote_linda') }}\n                                    </p>", "linda")
    text = sub(text, "Kofi Mensah, CEO.", "Kofi Mensah, {{ __('brn.role_ceo') }}", "kofi role")
    text = rsub(text, r"I love that I don’t waste time here\..*?</p>", "{{ __('brn.quote_kofi') }}\n                                    </p>", "kofi")
    text = sub(text, "Chijioke Okeke, Founder.", "Chijioke Okeke, {{ __('brn.role_founder') }}", "chijioke role")
    text = rsub(text, r"What sets this apart is the community\..*?</p>", "{{ __('brn.quote_chijioke') }}\n                                    </p>", "chijioke")
    text = sub(text, "Zanele Khumalo, Director.", "Zanele Khumalo, {{ __('brn.role_director') }}", "zanele role")
    text = rsub(text, r"Finally, a trusted network that filters for quality\..*?</p>", "{{ __('brn.quote_zanele') }}\n                                    </p>", "zanele")
    text = rsub(
        text,
        r"From closed-door roundtables to business retreats, every event\s*is designed to foster genuine collaboration and opportunity\.",
        "{{ __('brn.events_body') }}",
        "events",
    )
    text = sub(text, "See All Events", "{{ __('events.see_all') }}", "see all")
    text = sub(text, "Book A Seat", "{{ __('events.book_seat') }}", "book")
    text = rsub(
        text,
        r"Join a powerful ecosystem of referrals, relationships, and\s*results\.",
        "{{ __('brn.join_body') }}",
        "join",
    )
    text = sub(text, "Register Your Interest", "{{ __('brn.register_interest') }}", "interest")
    return text


def wire_consult(text):
    text = sub(
        text,
        "<h3>Let’s help you unlock growth clarity for your business.</h3>",
        "<h3>{{ __('consultation.clarity') }}</h3>",
        "clarity",
    )
    text = sub(text, "{{ route('consultation.application-forms') }}", "{{ locale_route('consultation.application-forms') }}", "forms route")
    text = rsub(text, r"Let’s get started", "{{ __('consultation.get_started') }}", "start")
    text = rsub(
        text,
        r"<span>The consultation</span> will analyze your challenges,\s*identify growth blockers, and design a roadmap to help you\s*scale sustainably in African markets\.",
        "{{ __('consultation.analyze') }}",
        "analyze",
    )
    return text


def wire_playbook(text):
    text = rsub(
        text,
        r"Your comprehensive guide to building and scaling successful businesses across Africa\.\s*Get expert insights, proven strategies, and actionable advice from successful entrepreneurs\.",
        "{{ __('playbook.subtitle') }}",
        "subtitle",
    )
    text = sub(text, "📥 Download Free Playbook", "📥 {{ __('playbook.download') }}", "download")
    text = sub(text, "Sending... (", "{{ __('playbook.sending') }} (", "sending")
    text = sub(text, "✅ Instant Download", "✅ {{ __('playbook.instant') }}", "instant")
    text = sub(text, "🔒 No Spam", "🔒 {{ __('playbook.no_spam') }}", "spam")
    text = sub(text, "P23 Africa's", "{{ __('playbook.brand') }}", "brand")
    text = sub(text, "FOUNDERS<br>PLAYBOOK", "{{ __('playbook.founders') }}<br>{{ __('playbook.playbook_word') }}", "founders")
    text = sub(
        text,
        "Your Complete Guide to Building & Scaling Successful Businesses in Africa",
        "{{ __('playbook.cover') }}",
        "cover",
    )
    text = rsub(
        text,
        r"Enter your details to receive the P23 Africa's Founders Playbook directly in\s*your inbox\.",
        "{{ __('playbook.inbox') }}",
        "inbox",
    )
    text = sub(text, "<label for=\"playbook_name\">Full Name *</label>", "<label for=\"playbook_name\">{{ __('playbook.full_name') }}</label>", "name")
    text = sub(text, "<label for=\"playbook_email\">Email Address *</label>", "<label for=\"playbook_email\">{{ __('playbook.email') }}</label>", "email")
    text = sub(text, "<label for=\"playbook_company\">Company Name *</label>", "<label for=\"playbook_company\">{{ __('playbook.company') }}</label>", "company")
    text = sub(text, '<span class="submit-text">Get My Playbook</span>', '<span class="submit-text">{{ __(\'playbook.get_mine\') }}</span>', "get")
    text = sub(text, "Submitting...", "{{ __('playbook.submitting') }}", "submitting")
    text = sub(text, "showError('nameError', 'Full name is required');", "showError('nameError', @json(__('playbook.name_required')));", "name err")
    text = sub(text, "showError('emailError', 'Email address is required');", "showError('emailError', @json(__('playbook.email_required')));", "email err")
    text = sub(text, "showError('emailError', 'Please enter a valid email address');", "showError('emailError', @json(__('playbook.email_invalid')));", "email bad")
    text = sub(text, "showError('companyError', 'Company name is required');", "showError('companyError', @json(__('playbook.company_required')));", "co err")
    text = sub(text, "'Playbook Sent Successfully!'", "@json(__('playbook.sent_title'))", "sent title")
    text = sub(
        text,
        "'The P23 Africa\\'s Founders Playbook has been sent to your email. Please check your inbox.'",
        "@json(__('playbook.sent_body'))",
        "sent body",
    )
    text = sub(text, "'Submission Failed'", "@json(__('playbook.fail_title'))", "fail")
    text = sub(
        text,
        "'An unexpected error occurred. Please try again.'",
        "@json(__('playbook.fail_body'))",
        "fail body",
    )
    text = sub(text, "'Request Taking Longer Than Expected'", "@json(__('playbook.slow_title'))", "slow")
    text = sub(
        text,
        "'Your playbook request is still being processed. Please check your email in a few minutes.'",
        "@json(__('playbook.slow_body'))",
        "slow body",
    )
    text = sub(text, "Playbook Sent! Check Your Email", "{{ __('playbook.sent_btn') }}", "sent btn")
    text = sub(text, "Try Again", "{{ __('playbook.try_again') }}", "retry")
    return text


def main():
    append_php("lang/en/quiz.php", "'questions'", QUIZ_EN)
    append_php("lang/fr/quiz.php", "'questions'", QUIZ_FR)
    append_php("lang/de/quiz.php", "'questions'", QUIZ_DE)
    append_php("lang/nl/quiz.php", "'questions'", QUIZ_NL)
    for loc, snip in (("en", BRN_EN), ("fr", BRN_FR), ("de", BRN_DE), ("nl", BRN_NL)):
        append_php(f"lang/{loc}/brn.php", "'connect_grow'", snip)
        append_php(f"lang/{loc}/consultation.php", "'clarity'", CONSULT[loc])
        append_php(f"lang/{loc}/playbook.php", "'subtitle'", PLAY[loc])

    patch("resources/views/sales.blade.php", wire_sales)
    patch("resources/views/sales-quiz.blade.php", lambda t: wire_quiz_pages(t, "sales"))
    patch("resources/views/start-quiz.blade.php", lambda t: wire_quiz_pages(t, "start"))
    patch("resources/views/include/quiz-questions-js.blade.php", wire_quiz_js)
    patch(
        "resources/views/quiz-answers/result-a.blade.php",
        lambda t: wire_result(
            t,
            "a",
            "Quiz Result (FRAGILE FUNNEL) | P23 Africa",
            "Your Revenue Stream Is Vulnerable—Small Cracks Today Could Become Big Breaks Tomorrow.",
            "FRAGILE FUNNEL",
        ),
    )
    patch(
        "resources/views/quiz-answers/result-b.blade.php",
        lambda t: wire_result(
            t,
            "b",
            "Quiz Result (HIDDEN POWERHOUSE) | P23 Africa",
            "Your revenue stream is vulnerable—small cracks today could become big breaks tomorrow.",
            "Hidden Powerhouse",
        ),
    )
    patch(
        "resources/views/quiz-answers/result-c.blade.php",
        lambda t: wire_result(
            t,
            "c",
            "Quiz Result (CLOUDY CLIMBER) | P23 Africa",
            "You’re on the path—but a little clarity could make your next step a leap.",
            "Cloudy Climber",
        ),
    )
    # legend and body strings that are unique
    def more_results(rel, pairs):
        def fn(text):
            for old, new, label in pairs:
                text = sub(text, old, new, label)
            return text
        patch(rel, fn)

    more_results(
        "resources/views/quiz-answers/result-a.blade.php",
        [
            ("Leads going cold", "{{ __('quiz.a_m1') }}", "a m1"),
            ("Lack of structure & tracking", "{{ __('quiz.a_m2') }}", "a m2"),
            ("Missed sales & bad CX", "{{ __('quiz.a_m3') }}", "a m3"),
            ("Common Risks At This Stage:", "{{ __('quiz.risks') }}", "risks"),
            ("Leads Going Cold Due To Lack Of Follow-Up", "{{ __('quiz.a_r1') }}", "r1"),
            ("Team Confusion Around Goals And Responsibilities", "{{ __('quiz.a_r2') }}", "r2"),
            ("Missed Sales Opportunities And Poor Customer Experience", "{{ __('quiz.a_r3') }}", "r3"),
            ("Marketing Spend With Little To No Return", "{{ __('quiz.a_r4') }}", "r4"),
            ("No Way To Measure Or Replicate Success", "{{ __('quiz.a_r5') }}", "r5"),
            ("What You Need:", "{{ __('quiz.need') }}", "need"),
            ("Next Step:", "{{ __('quiz.next') }}", "next"),
        ],
    )
    more_results(
        "resources/views/quiz-answers/result-b.blade.php",
        [
            ("System & conversion optimization", "{{ __('quiz.b_m1') }}", "b m1"),
            ("New growth channels & strategies", "{{ __('quiz.b_m2') }}", "b m2"),
            ("Refinement & performance insights", "{{ __('quiz.b_m3') }}", "b m3"),
            ("Signs Of Readiness To Scale:", "{{ __('quiz.ready') }}", "ready"),
            ("Consistent follow-ups and team clarity", "{{ __('quiz.b_r1') }}", "br1"),
            ("Tools and systems are in place and used well", "{{ __('quiz.b_r2') }}", "br2"),
            ("Clear understanding of customer journey", "{{ __('quiz.b_r3') }}", "br3"),
            ("Regular performance reviews and insights", "{{ __('quiz.b_r4') }}", "br4"),
            ("You’re proactive, not reactive", "{{ __('quiz.b_r5') }}", "br5"),
            ("What You Need:", "{{ __('quiz.need') }}", "need"),
            ("Next Step:", "{{ __('quiz.next') }}", "next"),
        ],
    )
    more_results(
        "resources/views/quiz-answers/result-c.blade.php",
        [
            ("Inconsistent tools & effort-based growth", "{{ __('quiz.c_m1') }}", "c m1"),
            ("Lack of visibility into performance", "{{ __('quiz.c_m2') }}", "c m2"),
            ("Follow-up delays & customer drop-off", "{{ __('quiz.c_m3') }}", "c m3"),
            ("What this often looks like:", "{{ __('quiz.looks') }}", "looks"),
            ("Follow-ups happening—but not timely", "{{ __('quiz.c_r1') }}", "cr1"),
            ("Team using tools inconsistently or inefficiently", "{{ __('quiz.c_r2') }}", "cr2"),
            ("Some growth—but mostly from effort, not strategy", "{{ __('quiz.c_r3') }}", "cr3"),
            ("Unclear visibility into sales or marketing performance", "{{ __('quiz.c_r4') }}", "cr4"),
            ("Customers falling out of the funnel without you knowing why", "{{ __('quiz.c_r5') }}", "cr5"),
            ("What You Need:", "{{ __('quiz.need') }}", "need"),
            ("Next Step:", "{{ __('quiz.next') }}", "next"),
        ],
    )

    def bodies(rel, pattern, repl, label):
        def fn(text):
            return rsub(text, pattern, repl, label)
        patch(rel, fn)

    bodies(
        "resources/views/quiz-answers/result-a.blade.php",
        r"Foundational Systems And Tools\..*?</p>",
        "{{ __('quiz.a_need') }}\n                                </p>",
        "a need",
    )
    bodies(
        "resources/views/quiz-answers/result-a.blade.php",
        r"Book Your Free Business Audit\..*?</p>",
        "{{ __('quiz.a_next') }}\n                                </p>",
        "a next",
    )
    bodies(
        "resources/views/quiz-answers/result-b.blade.php",
        r"A tailored growth plan\..*?</p>",
        "{{ __('quiz.b_need') }}\n                                </p>",
        "b need",
    )
    bodies(
        "resources/views/quiz-answers/result-b.blade.php",
        r"We can help you refine your growth plan.*?</p>",
        "{{ __('quiz.b_next') }}\n                                </p>",
        "b next",
    )
    bodies(
        "resources/views/quiz-answers/result-c.blade.php",
        r"A proper growth strategy with practical, affordable systems\..*?</p>",
        "{{ __('quiz.c_need') }}\n                                </p>",
        "c need",
    )
    bodies(
        "resources/views/quiz-answers/result-c.blade.php",
        r"Let’s review your business together\..*?</p>",
        "{{ __('quiz.c_next') }}\n                                </p>",
        "c next",
    )

    patch("resources/views/all-events.blade.php", lambda t: wire_events(t, "all"))
    patch("resources/views/show-event.blade.php", lambda t: wire_events(t, "show"))
    patch("resources/views/events/live.blade.php", lambda t: wire_events(t, "live"))
    patch("resources/views/include/book-seat.blade.php", lambda t: wire_events(t, "book"))
    patch("resources/views/feedback/form.blade.php", lambda t: wire_events(t, "feedback"))
    patch("resources/views/brn.blade.php", wire_brn_page)
    patch("resources/views/consultation/index.blade.php", wire_consult)
    patch("resources/views/playbook-landing.blade.php", wire_playbook)


if __name__ == "__main__":
    main()
