# -*- coding: utf-8 -*-
"""Build market-country language files and wire Nigeria and Ghana blades."""
import re
from pathlib import Path

ROOT = Path(r"c:\xampp\htdocs\P23 Africa")

# key, english source (whitespace-flexible), en, fr, de, nl
# html=True means the blade should print with {!! !!}
ITEMS = []


def add(key, en, fr, de, nl, html=False):
    ITEMS.append((key, en, en, fr, de, nl, html))


# Nigeria
add("ng.title", "Nigeria Market Entry Case Study: HVAC Manufacturer | P23 Africa",
    "Étude de cas d’entrée au Nigeria : fabricant de CVC | P23 Africa",
    "Fallstudie Markteintritt Nigeria: HLK-Hersteller | P23 Africa",
    "Casestudy markttoegang Nigeria: HVAC-fabrikant | P23 Africa")
add("ng.meta", "How P23 Africa helped an HVAC manufacturer test Nigerian market demand, vet trusted distributors, and deploy ground tracking software before committing capital.",
    "Comment P23 Africa a aidé un fabricant de CVC à tester la demande au Nigeria, à vérifier des distributeurs de confiance et à déployer un suivi terrain avant d’engager du capital.",
    "Wie P23 Africa einem HLK-Hersteller half, die Nachfrage in Nigeria zu testen, vertrauenswürdige Vertriebspartner zu prüfen und Tracking vor Ort einzusetzen, bevor Kapital gebunden wurde.",
    "Hoe P23 Africa een HVAC-fabrikant hielp de vraag in Nigeria te testen, betrouwbare distributeurs te toetsen en tracking ter plaatse in te zetten voordat kapitaal werd vastgelegd.")
add("ng.keywords", "Nigeria market entry, HVAC manufacturer Nigeria, distributor vetting Nigeria, demand testing Lagos Abuja, African commercial intelligence",
    "entrée marché Nigeria, fabricant CVC Nigeria, vérification distributeurs Nigeria, test de demande Lagos Abuja, intelligence commerciale Afrique",
    "Markteintritt Nigeria, HLK-Hersteller Nigeria, Vertriebspartnerprüfung Nigeria, Nachfragetest Lagos Abuja, kommerzielle Intelligenz Afrika",
    "markttoegang Nigeria, HVAC-fabrikant Nigeria, distributeurstoetsing Nigeria, vraagtest Lagos Abuja, commerciële intelligentie Afrika")
add("ng.og_title", "Nigeria Case Study: From Market Uncertainty to a Route into Nigeria | P23 Africa",
    "Étude de cas Nigeria : de l’incertitude à une voie d’accès | P23 Africa",
    "Fallstudie Nigeria: Von der Unsicherheit zu einem Weg in den Markt | P23 Africa",
    "Casestudy Nigeria: van onzekerheid naar een route de markt in | P23 Africa")
add("ng.og_desc", "Discover how outbound demand testing and 10-point distributor due diligence opened Nigeria for an industrial manufacturer.",
    "Découvrez comment un test de demande sortant et une diligence en 10 points ont ouvert le Nigeria à un fabricant industriel.",
    "Erfahren Sie, wie ein ausgehender Nachfragetest und eine 10-Punkte-Prüfung Nigeria für einen Industriehersteller geöffnet haben.",
    "Ontdek hoe een outbound vraagtest en een toetsing in 10 punten Nigeria opende voor een industriële fabrikant.")
add("ng.crumb", "Nigeria HVAC Case Study",
    "Étude de cas CVC Nigeria",
    "HLK-Fallstudie Nigeria",
    "HVAC-casestudy Nigeria")
add("ng.sector", "HVAC & Industrial Manufacturing",
    "CVC et fabrication industrielle",
    "HLK und industrielle Fertigung",
    "HVAC en industriële productie")
add("ng.tag", "Pre-Capital Demand Testing",
    "Test de demande avant capital",
    "Nachfragetest vor Kapitaleinsatz",
    "Vraagtest vóór kapitaalinzet")
add("ng.hero", "From market uncertainty to a route into Nigeria.",
    "De l’incertitude du marché à une voie d’accès au Nigeria.",
    "Von der Marktunsicherheit zu einem Weg nach Nigeria.",
    "Van marktonzekerheid naar een route Nigeria in.")
add("ng.hero_sub", "Entering a new market is a significant commitment. For an HVAC manufacturer looking to expand into Nigeria, the first question wasn’t simply <em>“Can we sell here?”</em> It was: <strong>“Is there genuine demand, who will buy from us, what will they pay, and who can we trust to take our products to market?”</strong>",
    "Entrer sur un nouveau marché est un engagement important. Pour un fabricant de CVC qui voulait s’étendre au Nigeria, la première question n’était pas simplement <em>« Pouvons-nous vendre ici ? »</em> Elle était : <strong>« Y a-t-il une demande réelle, qui achètera chez nous, quel prix paiera-t-il, et à qui pouvons-nous confier nos produits ? »</strong>",
    "Der Eintritt in einen neuen Markt ist eine große Verpflichtung. Für einen HLK-Hersteller mit Expansionsplänen nach Nigeria lautete die erste Frage nicht einfach <em>„Können wir hier verkaufen?“</em> Sie lautete: <strong>„Gibt es echte Nachfrage, wer kauft bei uns, was wird gezahlt, und wem können wir unsere Produkte anvertrauen?“</strong>",
    "Een nieuwe markt betreden is een grote stap. Voor een HVAC-fabrikant die naar Nigeria wilde uitbreiden, was de eerste vraag niet simpelweg <em>„Kunnen we hier verkopen?“</em> De vraag was: <strong>„Is er echte vraag, wie koopt bij ons, wat betalen ze, en wie kunnen we vertrouwen om onze producten op de markt te brengen?“</strong>",
    html=True)
add("ng.hero_2", "P23 Africa helped answer those questions before significant capital was committed — then supported the client in turning the opportunity into a functioning route to market.",
    "P23 Africa a répondu à ces questions avant qu’un capital important ne soit engagé, puis a aidé le client à transformer l’opportunité en une voie d’accès opérationnelle.",
    "P23 Africa beantwortete diese Fragen, bevor erhebliches Kapital gebunden wurde, und half dem Kunden danach, die Chance in einen funktionierenden Marktzugang zu verwandeln.",
    "P23 Africa beantwoordde die vragen voordat aanzienlijk kapitaal werd vastgelegd en hielp de klant daarna de kans om te zetten in een werkende route naar de markt.")
add("ng.capex", "Misallocated Capex", "Capex mal alloué", "Fehlgeleitete Investitionen", "Verkeerd ingezet kapitaal")
add("ng.real", "Real", "Réel", "Echt", "Echt")
add("ng.feedback", "Direct Commercial Feedback", "Retour commercial direct", "Direktes kommerzielles Feedback", "Directe commerciële feedback")
add("ng.checks", "Checks", "Contrôles", "Prüfungen", "Controles")
add("ng.diligence", "Distributor Due Diligence Points", "Points de diligence distributeur", "Prüfpunkte für Vertriebspartner", "Toetspunten voor distributeurs")
add("ng.ground", "Ground", "Terrain", "Vor Ort", "Ter plaatse")
add("ng.tracking", "Operational Tech Tracking", "Suivi opérationnel", "Operatives Tracking", "Operationele tracking")
add("ng.context", "The Context & Dilemma", "Le contexte et le dilemme", "Kontext und Dilemma", "Context en dilemma")
add("ng.context_title", "Testing the Nigerian market before investing",
    "Tester le marché nigérian avant d’investir",
    "Den nigerianischen Markt testen, bevor investiert wird",
    "De Nigeriaanse markt testen vóór een investering")
add("ng.context_1", "The client, an HVAC manufacturer, was exploring expansion into Nigeria and wanted to understand the commercial opportunity before committing resources to establishing a presence.",
    "Le client, un fabricant de CVC, étudiait une expansion au Nigeria et voulait comprendre l’opportunité commerciale avant d’engager des ressources pour s’y implanter.",
    "Der Kunde, ein HLK-Hersteller, prüfte eine Expansion nach Nigeria und wollte die kommerzielle Chance verstehen, bevor Ressourcen für eine Präsenz gebunden wurden.",
    "De klant, een HVAC-fabrikant, onderzocht uitbreiding naar Nigeria en wilde de commerciële kans begrijpen voordat middelen voor een aanwezigheid werden ingezet.")
add("ng.context_2", "They needed more than a market report. They needed <strong>evidence from the market</strong>:",
    "Ils avaient besoin de plus qu’un rapport de marché. Ils avaient besoin de <strong>preuves venues du marché</strong> :",
    "Sie brauchten mehr als einen Marktbericht. Sie brauchten <strong>Belege aus dem Markt</strong>:",
    "Ze hadden meer nodig dan een marktrapport. Ze hadden <strong>bewijs uit de markt</strong> nodig:",
    html=True)
add("ng.q1", "Who was actually interested in the product?", "Qui s’intéressait vraiment au produit ?", "Wer interessierte sich tatsächlich für das Produkt?", "Wie had echt interesse in het product?")
add("ng.q2", "Which types of businesses were buying?", "Quels types d’entreprises achetaient ?", "Welche Unternehmen kauften?", "Welke soorten bedrijven kochten?")
add("ng.q3", "What were customers currently paying?", "Que payaient actuellement les clients ?", "Was zahlten Kunden derzeit?", "Wat betaalden klanten op dat moment?")
add("ng.q4", "Where was demand concentrated?", "Où la demande se concentrait-elle ?", "Wo konzentrierte sich die Nachfrage?", "Waar zat de vraag?")
add("ng.q5", "Who could distribute the products?", "Qui pouvait distribuer les produits ?", "Wer konnte die Produkte vertreiben?", "Wie kon de producten distribueren?")
add("ng.q6", "Which distributors could be trusted to represent the brand & manage stock?",
    "Quels distributeurs pouvaient représenter la marque et gérer le stock ?",
    "Welchen Vertriebspartnern konnte man Marke und Lager anvertrauen?",
    "Welke distributeurs konden het merk vertegenwoordigen en de voorraad beheren?")
add("ng.brought", "P23 was brought in to test the opportunity and develop a practical route into the Nigerian market.",
    "P23 a été mandaté pour tester l’opportunité et construire une voie pratique vers le marché nigérian.",
    "P23 wurde beauftragt, die Chance zu testen und einen praktischen Weg in den nigerianischen Markt zu entwickeln.",
    "P23 werd ingeschakeld om de kans te testen en een praktische route naar de Nigeriaanse markt te bouwen.")
add("ng.playbook", "The 4-Stage Execution Playbook", "Le plan d’exécution en 4 étapes", "Der Umsetzungsplan in 4 Schritten", "Het uitvoeringsplan in 4 stappen")
add("ng.playbook_title", "From demand testing to market entry",
    "Du test de demande à l’entrée sur le marché",
    "Vom Nachfragetest zum Markteintritt",
    "Van vraagtest naar markttoegang")
add("ng.s1", "We tested the market", "Nous avons testé le marché", "Wir haben den Markt getestet", "Wij hebben de markt getest")
add("ng.s1_lead", "We designed an outbound demand-testing programme focused on real potential buyers and distributors. Rather than relying solely on surveys or secondary research, we went directly to the market.",
    "Nous avons conçu un programme de test de demande sortant, centré sur de vrais acheteurs et distributeurs potentiels. Plutôt que de nous appuyer seulement sur des enquêtes ou des études secondaires, nous sommes allés directement au marché.",
    "Wir haben ein ausgehendes Nachfragetest-Programm für echte potenzielle Käufer und Vertriebspartner entworfen. Statt uns nur auf Umfragen oder Sekundärforschung zu stützen, sind wir direkt in den Markt gegangen.",
    "Wij ontwierpen een outbound vraagtest gericht op echte potentiële kopers en distributeurs. In plaats van alleen op enquêtes of bureauonderzoek te leunen, gingen we rechtstreeks de markt op.")
add("ng.s1_body", "We identified and engaged relevant businesses across the target market, including potential distributors, contractors and end users. The objective was to generate real commercial signal from real businesses.",
    "Nous avons identifié et contacté des entreprises pertinentes sur le marché cible, y compris des distributeurs potentiels, des entrepreneurs et des utilisateurs finaux. L’objectif était d’obtenir un signal commercial réel auprès d’entreprises réelles.",
    "Wir haben relevante Unternehmen im Zielmarkt identifiziert und angesprochen, darunter mögliche Vertriebspartner, Auftragnehmer und Endnutzer. Ziel war ein echtes kommerzielles Signal von echten Unternehmen.",
    "Wij hebben relevante bedrijven in de doelmarkt gevonden en benaderd, waaronder mogelijke distributeurs, aannemers en eindgebruikers. Het doel was een echt commercieel signaal van echte bedrijven.")
add("ng.c1", "Who was actively buying HVAC products", "Qui achetait activement des produits de CVC", "Wer aktiv HLK-Produkte kaufte", "Wie actief HVAC-producten kocht")
add("ng.c2", "What products they were currently using", "Quels produits ils utilisaient", "Welche Produkte sie nutzten", "Welke producten ze gebruikten")
add("ng.c3", "What price points they were paying", "Quels prix ils payaient", "Welche Preise sie zahlten", "Welke prijzen ze betaalden")
add("ng.c4", "Which businesses were interested in the offering", "Quelles entreprises s’intéressaient à l’offre", "Welche Unternehmen Interesse an dem Angebot hatten", "Welke bedrijven interesse hadden in het aanbod")
add("ng.c5", "What objections or barriers existed", "Quelles objections ou barrières existaient", "Welche Einwände oder Hürden es gab", "Welke bezwaren of drempels er waren")
add("ng.c6", "Which customer segments showed strongest potential", "Quels segments montraient le plus fort potentiel", "Welche Kundensegmente das stärkste Potenzial zeigten", "Welke klantsegmenten de sterkste potentie toonden")
add("ng.c7", "Which businesses were actively seeking alternatives", "Quelles entreprises cherchaient activement des alternatives", "Welche Unternehmen aktiv Alternativen suchten", "Welke bedrijven actief alternatieven zochten")
add("ng.s1_result", "The result was a much clearer picture of where the opportunity existed, and where it didn’t.",
    "Le résultat était une image bien plus claire de l’endroit où l’opportunité existait, et de celui où elle n’existait pas.",
    "Das Ergebnis war ein viel klareres Bild davon, wo die Chance lag und wo nicht.",
    "Het resultaat was een veel duidelijker beeld van waar de kans lag, en waar niet.")
add("ng.intel", "Turning conversations into market intelligence",
    "Transformer les conversations en intelligence de marché",
    "Gespräche in Marktintelligenz verwandeln",
    "Gesprekken omzetten in marktinformatie")
add("ng.intel_lead", "The value wasn’t simply in generating leads. Every conversation provided information. We categorised responses to distinguish genuine commercial interest from general enquiries and non-engagement.",
    "La valeur n’était pas seulement de générer des prospects. Chaque conversation apportait de l’information. Nous avons classé les réponses pour distinguer un intérêt commercial réel des demandes générales et de l’absence d’engagement.",
    "Der Wert lag nicht nur in der Leadgewinnung. Jedes Gespräch lieferte Informationen. Wir haben Antworten geordnet, um echtes kommerzielles Interesse von allgemeinen Anfragen und fehlendem Engagement zu trennen.",
    "De waarde zat niet alleen in het genereren van leads. Elk gesprek leverde informatie. Wij ordenden de reacties om echte commerciële interesse te onderscheiden van algemene vragen en gebrek aan betrokkenheid.")
add("ng.intel_body", "That allowed us to identify clear patterns across five vital commercial dimensions:",
    "Cela nous a permis d’identifier des tendances claires sur cinq dimensions commerciales essentielles :",
    "So konnten wir klare Muster über fünf zentrale kommerzielle Dimensionen erkennen:",
    "Daardoor zagen we duidelijke patronen over vijf essentiële commerciële dimensies:")
add("ng.d1", "01. Demand", "01. Demande", "01. Nachfrage", "01. Vraag")
add("ng.d1t", "Customer Groups", "Groupes de clients", "Kundengruppen", "Klantgroepen")
add("ng.d1b", "Which customer segments were actively interested vs merely curious?",
    "Quels segments étaient vraiment intéressés, et lesquels étaient seulement curieux ?",
    "Welche Segmente waren aktiv interessiert und welche nur neugierig?",
    "Welke segmenten waren actief geïnteresseerd en welke alleen nieuwsgierig?")
add("ng.d2", "02. Pricing", "02. Prix", "02. Preis", "02. Prijs")
add("ng.d2t", "Price Benchmarks", "Repères de prix", "Preisbenchmarks", "Prijsbenchmarks")
add("ng.d2b", "What were businesses currently paying for comparable imported products?",
    "Que payaient les entreprises pour des produits importés comparables ?",
    "Was zahlten Unternehmen derzeit für vergleichbare Importprodukte?",
    "Wat betaalden bedrijven voor vergelijkbare geïmporteerde producten?")
add("ng.d3", "03. Fit", "03. Adéquation", "03. Passung", "03. Aansluiting")
add("ng.d3t", "Product Comparison", "Comparaison de produits", "Produktvergleich", "Productvergelijking")
add("ng.d3b", "How did the client's offering compare with what the market already trusted?",
    "Comment l’offre du client se comparait-elle à ce que le marché faisait déjà confiance ?",
    "Wie stand das Angebot des Kunden zu dem, dem der Markt bereits vertraute?",
    "Hoe verhield het aanbod van de klant zich tot wat de markt al vertrouwde?")
add("ng.d4", "04. Barriers", "04. Freins", "04. Hürden", "04. Drempels")
add("ng.d4t", "Objection Mapping", "Cartographie des objections", "Einwandlandkarte", "Bezwaren in kaart")
add("ng.d4b", "What logistical, currency, or technical concerns prevented deals?",
    "Quelles préoccupations logistiques, de change ou techniques bloquaient les affaires ?",
    "Welche logistischen, währungsbezogenen oder technischen Bedenken verhinderten Abschlüsse?",
    "Welke logistieke, valutaire of technische zorgen blokkeerden deals?")
add("ng.d5", "05. Timing", "05. Calendrier", "05. Zeitpunkt", "05. Timing")
add("ng.d5t", "Purchase Horizon", "Horizon d’achat", "Kaufhorizont", "Aankoophorizon")
add("ng.d5b", "Which prospects had immediate requirements vs long-term cycles?",
    "Quels prospects avaient un besoin immédiat, et lesquels un cycle long ?",
    "Welche Interessenten hatten sofortigen Bedarf und welche lange Zyklen?",
    "Welke prospects hadden een directe behoefte en welke een lange cyclus?")
add("ng.intel_result", "The result was a much more informed view of the Nigerian opportunity, based on conversations with the market rather than assumptions about it.",
    "Le résultat était une vision bien plus informée de l’opportunité nigériane, fondée sur des conversations avec le marché plutôt que sur des hypothèses.",
    "Das Ergebnis war ein deutlich fundierteres Bild der Chance in Nigeria, gestützt auf Gespräche mit dem Markt statt auf Annahmen.",
    "Het resultaat was een veel beter onderbouwd beeld van de kans in Nigeria, gebaseerd op gesprekken met de markt in plaats van aannames.")
add("ng.s2", "We identified and vetted distributors",
    "Nous avons identifié et vérifié les distributeurs",
    "Wir haben Vertriebspartner identifiziert und geprüft",
    "Wij hebben distributeurs gevonden en getoetst")
add("ng.s2_lead", "Once the demand picture became clearer, the next question was how to get the product into the market. We identified potential distributors and assessed them against the requirements of the business.",
    "Une fois la demande plus claire, la question suivante était de savoir comment faire entrer le produit sur le marché. Nous avons identifié des distributeurs potentiels et les avons évalués selon les exigences de l’entreprise.",
    "Als das Nachfragebild klarer war, lautete die nächste Frage, wie das Produkt in den Markt kommt. Wir haben mögliche Vertriebspartner identifiziert und an den Anforderungen des Unternehmens gemessen.",
    "Toen het vraagbeeld duidelijker werd, was de volgende vraag hoe het product de markt op kwam. Wij vonden mogelijke distributeurs en toetsten hen aan de eisen van het bedrijf.")
add("ng.s2_body", "This went far beyond compiling a generic directory of trading companies. We examined 10 critical operational factors:",
    "Cela allait bien au-delà d’un simple annuaire d’entreprises commerciales. Nous avons examiné 10 facteurs opérationnels critiques :",
    "Das ging weit über ein allgemeines Verzeichnis von Handelsfirmen hinaus. Wir prüften 10 kritische operative Faktoren:",
    "Dat ging veel verder dan een algemene lijst van handelsbedrijven. Wij bekeken 10 kritieke operationele factoren:")
add("ng.v1", "Business history & CAC registration", "Historique de l’entreprise et immatriculation CAC", "Unternehmenshistorie und CAC-Registrierung", "Bedrijfsgeschiedenis en CAC-registratie")
add("ng.v2", "Existing product portfolio", "Portefeuille de produits existant", "Bestehendes Produktportfolio", "Bestaand productportfolio")
add("ng.v3", "Complementary brands & potential conflicts", "Marques complémentaires et conflits potentiels", "Ergänzende Marken und mögliche Konflikte", "Aanvullende merken en mogelijke conflicten")
add("ng.v4", "Proven frontline sales capability", "Capacité de vente terrain démontrée", "Nachgewiesene Vertriebsstärke im Feld", "Bewezen verkoopkracht in het veld")
add("ng.v5", "Geographic reach across Lagos & regional hubs", "Couverture de Lagos et des pôles régionaux", "Geografische Reichweite in Lagos und regionalen Zentren", "Bereik in Lagos en regionale knooppunten")
add("ng.v6", "Warehousing & secure storage capability", "Entreposage et stockage sécurisé", "Lagerung und sichere Aufbewahrung", "Opslag en beveiligde voorraad")
add("ng.v7", "Key market relationships & contractor trust", "Relations clés et confiance des entrepreneurs", "Wichtige Marktbeziehungen und Vertrauen der Auftragnehmer", "Belangrijke relaties en vertrouwen van aannemers")
add("ng.v8", "Commercial credit capacity & balance sheet", "Capacité de crédit et bilan", "Kommerzielle Kreditfähigkeit und Bilanz", "Kredietcapaciteit en balans")
add("ng.v9", "Existing international supplier relationships", "Relations existantes avec des fournisseurs internationaux", "Bestehende Beziehungen zu internationalen Lieferanten", "Bestaande relaties met internationale leveranciers")
add("ng.v10", "Overall cultural & strategic suitability", "Adéquation culturelle et stratégique globale", "Kulturelle und strategische Gesamtpassung", "Culturele en strategische geschiktheid")
add("ng.s2_end", "Where appropriate, we also carried out direct reference checks with existing supplier relationships. The objective was to eliminate the risk of choosing a partner simply because they looked impressive on paper.",
    "Lorsque c’était pertinent, nous avons aussi vérifié directement les références auprès des fournisseurs existants. L’objectif était d’éviter de choisir un partenaire seulement parce qu’il était impressionnant sur le papier.",
    "Wo sinnvoll, haben wir auch direkte Referenzen bei bestehenden Lieferantenbeziehungen eingeholt. Ziel war, das Risiko zu vermeiden, einen Partner nur wegen eines überzeugenden Papiers zu wählen.",
    "Waar dat paste, hebben we ook rechtstreeks referenties bij bestaande leveranciersrelaties nagetrokken. Het doel was het risico te vermijden een partner te kiezen alleen omdat die op papier indrukwekkend leek.")
add("ng.s3", "We facilitated the market entry", "Nous avons facilité l’entrée sur le marché", "Wir haben den Markteintritt begleitet", "Wij hebben de markttoegang begeleid")
add("ng.s3_lead", "With the market opportunity validated and a suitable distributor identified, P23 facilitated the relationship between the manufacturer and the Nigerian partner.",
    "L’opportunité validée et un distributeur adapté identifié, P23 a facilité la relation entre le fabricant et le partenaire nigérian.",
    "Mit validierter Marktchance und einem geeigneten Vertriebspartner hat P23 die Beziehung zwischen Hersteller und nigerianischem Partner begleitet.",
    "Met een gevalideerde kans en een geschikte distributeur begeleidde P23 de relatie tussen de fabrikant en de Nigeriaanse partner.")
add("ng.transition", "Project Transition:", "Transition du projet :", "Projektübergang:", "Projectovergang:")
add("ng.from", "From", "De", "Von", "Van")
add("ng.may", "“There may be a market”", "« Il y a peut-être un marché »", "„Es könnte einen Markt geben“", "„Er is misschien een markt“")
add("ng.to", "“We have a functioning route into the market.”", "« Nous avons une voie d’accès qui fonctionne. »", "„Wir haben einen funktionierenden Weg in den Markt.“", "„We hebben een werkende route de markt in.“")
add("ng.s3_end", "P23 supported the client through the process of developing the distributor relationship, structuring agreements, and establishing the operational foundations for commercial activity in Nigeria.",
    "P23 a accompagné le client dans la relation avec le distributeur, la structuration des accords et la mise en place des bases opérationnelles de l’activité commerciale au Nigeria.",
    "P23 begleitete den Kunden beim Aufbau der Vertriebsbeziehung, bei der Vertragsstruktur und bei den operativen Grundlagen für die Tätigkeit in Nigeria.",
    "P23 begeleidde de klant bij de distributeursrelatie, de opzet van afspraken en de operationele basis voor commerciële activiteit in Nigeria.")
add("ng.s4", "We helped maintain visibility on the ground",
    "Nous avons aidé à garder la visibilité sur le terrain",
    "Wir haben geholfen, die Sicht vor Ort zu behalten",
    "Wij hielpen het zicht ter plaatse te behouden")
add("ng.s4_lead", "Entering the market is only the beginning. Once products are in-market, the challenge becomes maintaining visibility over what is happening on the ground:",
    "Entrer sur le marché n’est qu’un début. Une fois les produits en place, le défi est de garder la visibilité sur ce qui se passe sur le terrain :",
    "Der Markteintritt ist nur der Anfang. Sobald Produkte im Markt sind, wird die Sicht darauf zur Herausforderung, was vor Ort geschieht:",
    "De markt betreden is pas het begin. Zodra producten in de markt zijn, wordt het de uitdaging om te zien wat er ter plaatse gebeurt:")
add("ng.g1", "Where are the products?", "Où sont les produits ?", "Wo sind die Produkte?", "Waar zijn de producten?")
add("ng.g2", "What are agents doing?", "Que font les agents ?", "Was tun die Vertreter?", "Wat doen de agenten?")
add("ng.g3", "Which leads are advancing?", "Quels prospects avancent ?", "Welche Leads kommen voran?", "Welke leads gaan vooruit?")
add("ng.g4", "What activity is taking place?", "Quelle activité a lieu ?", "Welche Aktivität findet statt?", "Welke activiteit vindt plaats?")
add("ng.g5", "Where might stock or sales opportunities be at risk?",
    "Où le stock ou les opportunités de vente pourraient-ils être en risque ?",
    "Wo könnten Lager oder Verkaufschancen gefährdet sein?",
    "Waar lopen voorraad of verkoopkansen risico?")
add("ng.s4_soft", "To support this, we implemented <strong>P23’s tracking software</strong> to provide greater operational oversight of activity in the market.",
    "Pour cela, nous avons mis en place <strong>le logiciel de suivi de P23</strong> afin d’offrir une meilleure supervision de l’activité sur le marché.",
    "Dafür haben wir <strong>die Tracking-Software von P23</strong> eingesetzt, um die Aktivität im Markt besser zu überblicken.",
    "Daarvoor hebben we <strong>de trackingsoftware van P23</strong> ingezet om de activiteit in de markt beter te overzien.",
    html=True)
add("ng.s4_end", "The system helps the client monitor activity through its agents on the ground, giving the business greater visibility while reducing some of the risks associated with managing a market remotely. It also creates a clearer view of opportunities, enabling the team to follow up on activity and maximise potential sales.",
    "Le système aide le client à suivre l’activité de ses agents sur le terrain, avec plus de visibilité et moins de risques liés à la gestion à distance. Il clarifie aussi les opportunités, pour que l’équipe relance l’activité et maximise les ventes potentielles.",
    "Das System hilft dem Kunden, die Aktivität der Vertreter vor Ort zu verfolgen, mit mehr Sicht und weniger Risiko der Fernsteuerung. Es macht Chancen klarer, damit das Team nachfasst und mögliche Verkäufe ausschöpft.",
    "Het systeem helpt de klant de activiteit van agenten ter plaatse te volgen, met meer zicht en minder risico van beheer op afstand. Het maakt kansen ook duidelijker, zodat het team kan opvolgen en mogelijke verkopen kan benutten.")
add("ng.outcome_label", "The Outcome", "Le résultat", "Das Ergebnis", "Het resultaat")
add("ng.outcome_title", "A market opportunity became a functioning route to market.",
    "Une opportunité de marché est devenue une voie d’accès qui fonctionne.",
    "Aus einer Marktchance wurde ein funktionierender Weg in den Markt.",
    "Een marktkans werd een werkende route naar de markt.")
add("ng.outcome_body", "Through a combination of demand testing, market intelligence, distributor vetting, relationship development and operational oversight, P23 helped the client move through the key stages of Nigerian market entry:",
    "Par le test de demande, l’intelligence de marché, la vérification des distributeurs, le développement de la relation et le suivi opérationnel, P23 a aidé le client à franchir les étapes clés de l’entrée au Nigeria :",
    "Mit Nachfragetest, Marktintelligenz, Partnerprüfung, Beziehungsaufbau und operativer Aufsicht half P23 dem Kunden durch die zentralen Schritte des Markteintritts in Nigeria:",
    "Met vraagtest, marktinformatie, distributeurstoetsing, relatieopbouw en operationeel toezicht hielp P23 de klant door de belangrijkste stappen van markttoegang in Nigeria:")
add("ng.o1", "Market demand tested directly with buyers", "Demande testée directement auprès des acheteurs", "Nachfrage direkt bei Käufern getestet", "Vraag rechtstreeks bij kopers getest")
add("ng.o2", "Customer & pricing intelligence gathered", "Intelligence clients et prix recueillie", "Kunden- und Preisinformationen gesammelt", "Klant- en prijsinformatie verzameld")
add("ng.o3", "Potential distributors vetted & assessed", "Distributeurs potentiels vérifiés et évalués", "Mögliche Vertriebspartner geprüft und bewertet", "Mogelijke distributeurs getoetst en beoordeeld")
add("ng.o4", "High-trust distributor relationship facilitated", "Relation de confiance avec un distributeur facilitée", "Vertrauensvolle Vertriebsbeziehung aufgebaut", "Vertrouwensrelatie met een distributeur opgebouwd")
add("ng.o5", "Operational tracking software deployed on the ground", "Logiciel de suivi opérationnel déployé sur le terrain", "Operative Tracking-Software vor Ort eingesetzt", "Operationele trackingsoftware ter plaatse ingezet")
add("ng.outcome_end", "And the client was able to enter the Nigerian market with significantly more information and visibility than they would have had from desk research alone.",
    "Le client a ainsi pu entrer sur le marché nigérian avec bien plus d’information et de visibilité que ne l’aurait permis une simple recherche documentaire.",
    "So konnte der Kunde mit deutlich mehr Information und Sicht in den nigerianischen Markt eintreten, als reine Schreibtischrecherche geliefert hätte.",
    "Zo kon de klant de Nigeriaanse markt betreden met veel meer informatie en zicht dan bureauonderzoek alleen had gegeven.")
add("ng.principles", "Strategic Principles", "Principes stratégiques", "Strategische Prinzipien", "Strategische principes")
add("ng.principles_title", "What this means for businesses entering Nigeria",
    "Ce que cela signifie pour les entreprises qui entrent au Nigeria",
    "Was das für Unternehmen bedeutet, die nach Nigeria gehen",
    "Wat dit betekent voor bedrijven die Nigeria betreden")
add("ng.principles_body", "Market entry doesn’t have to begin with a major investment. There is a more practical way to approach the decision:",
    "L’entrée sur un marché n’a pas à commencer par un investissement majeur. Il existe une façon plus pratique d’aborder la décision :",
    "Ein Markteintritt muss nicht mit einer großen Investition beginnen. Es gibt einen praktischeren Weg zur Entscheidung:",
    "Markttoegang hoeft niet te beginnen met een grote investering. Er is een praktischere manier om de beslissing te benaderen:")
add("ng.t1", "01. Test Demand", "01. Tester la demande", "01. Nachfrage testen", "01. Vraag testen")
add("ng.t1b", "Find out what the market is actually saying before shipping containers or signing long leases.",
    "Découvrez ce que le marché dit vraiment avant d’expédier des conteneurs ou de signer de longs baux.",
    "Finden Sie heraus, was der Markt wirklich sagt, bevor Container verschifft oder lange Mietverträge unterschrieben werden.",
    "Ontdek wat de markt echt zegt voordat containers worden verscheept of lange huurcontracten worden getekend.")
add("ng.t2", "02. Validate Value", "02. Valider la valeur", "02. Wert prüfen", "02. Waarde toetsen")
add("ng.t2b", "Understand who is interested, what they currently buy and what they are prepared to pay.",
    "Comprenez qui est intéressé, ce qu’il achète déjà et ce qu’il est prêt à payer.",
    "Verstehen Sie, wer interessiert ist, was heute gekauft wird und was man zu zahlen bereit ist.",
    "Begrijp wie geïnteresseerd is, wat ze nu kopen en wat ze willen betalen.")
add("ng.t3", "03. Vet Partners", "03. Vérifier les partenaires", "03. Partner prüfen", "03. Partners toetsen")
add("ng.t3b", "Identify and rigorously assess the businesses that could help you reach the market reliably.",
    "Identifiez et évaluez rigoureusement les entreprises qui peuvent vous aider à atteindre le marché de façon fiable.",
    "Identifizieren und prüfen Sie gründlich die Unternehmen, die Ihnen einen verlässlichen Marktzugang geben können.",
    "Vind en toets grondig de bedrijven die u betrouwbaar toegang tot de markt kunnen geven.")
add("ng.t4", "04. Maintain Eyes", "04. Garder la visibilité", "04. Sicht behalten", "04. Zicht houden")
add("ng.t4b", "Use the right systems and local support to understand what is happening once you’re in-market.",
    "Utilisez les bons systèmes et un appui local pour comprendre ce qui se passe une fois sur le marché.",
    "Nutzen Sie die richtigen Systeme und lokale Unterstützung, um zu verstehen, was nach dem Eintritt geschieht.",
    "Gebruik de juiste systemen en lokale steun om te begrijpen wat er gebeurt zodra u in de markt bent.")
add("ng.approach", "This is how P23 approaches market entry: Understand. Validate. Connect. Execute.",
    "Voici comment P23 aborde l’entrée sur un marché : Comprendre. Valider. Relier. Exécuter.",
    "So geht P23 den Markteintritt an: Verstehen. Prüfen. Verbinden. Umsetzen.",
    "Zo pakt P23 markttoegang aan: Begrijpen. Toetsen. Verbinden. Uitvoeren.")
add("ng.approach_body", "We don’t believe market entry should be based on assumptions. We help businesses gather market intelligence, test commercial demand, build the right relationships and put the infrastructure in place to support execution.",
    "Nous ne pensons pas que l’entrée sur un marché doive reposer sur des hypothèses. Nous aidons les entreprises à recueillir l’intelligence de marché, tester la demande, construire les bonnes relations et mettre en place l’infrastructure d’exécution.",
    "Wir glauben nicht, dass ein Markteintritt auf Annahmen beruhen sollte. Wir helfen Unternehmen, Marktintelligenz zu sammeln, Nachfrage zu testen, die richtigen Beziehungen aufzubauen und die Infrastruktur für die Umsetzung zu schaffen.",
    "Wij geloven niet dat markttoegang op aannames moet rusten. Wij helpen bedrijven marktinformatie te verzamelen, de vraag te testen, de juiste relaties op te bouwen en de infrastructuur voor uitvoering neer te zetten.")
add("ng.next", "Next Case Study Dossier", "Prochain dossier", "Nächstes Falldossier", "Volgend casusdossier")
add("ng.next_title", "Ghana: Construction Equipment Platform",
    "Ghana : plateforme d’équipement de construction",
    "Ghana: Plattform für Baugeräte",
    "Ghana: platform voor bouwmaterieel")
add("ng.next_body", "Scaling from multi-country testing to 5,000 contractors and 2,150 renters in six months.",
    "De tests multi-pays à 5 000 entrepreneurs et 2 150 locataires en six mois.",
    "Vom Mehrländetest zu 5.000 Auftragnehmern und 2.150 Mietern in sechs Monaten.",
    "Van tests in meerdere landen naar 5.000 aannemers en 2.150 huurders in zes maanden.")
add("ng.next_link", "Explore Ghana Case Study", "Voir l’étude de cas Ghana", "Ghana-Fallstudie ansehen", "Ghana-casestudy bekijken")
add("ng.access", "Nigeria Market Access", "Accès au marché nigérian", "Marktzugang Nigeria", "Markttoegang Nigeria")
add("ng.consider", "Considering Nigeria?", "Vous envisagez le Nigeria ?", "Nigeria im Blick?", "Denkt u aan Nigeria?")
add("ng.cta", "If you’re exploring the Nigerian market and want to understand the opportunity before committing significant resources, let’s talk.",
    "Si vous explorez le marché nigérian et voulez comprendre l’opportunité avant d’engager des ressources importantes, parlons-en.",
    "Wenn Sie den nigerianischen Markt prüfen und die Chance verstehen wollen, bevor erhebliche Ressourcen gebunden werden, lassen Sie uns sprechen.",
    "Als u de Nigeriaanse markt verkent en de kans wilt begrijpen voordat u veel middelen inzet, laten we praten.")
add("ng.talk", "Start a conversation with P23 Africa", "Démarrer une conversation avec P23 Africa", "Gespräch mit P23 Africa starten", "Een gesprek met P23 Africa starten")
add("ng.explore", "Explore All Capabilities", "Voir toutes les capacités", "Alle Leistungen ansehen", "Alle mogelijkheden bekijken")
add("ng.footprint", "Lagos & Abuja Footprint", "Présence à Lagos et Abuja", "Präsenz in Lagos und Abuja", "Aanwezigheid in Lagos en Abuja")
add("ng.foot_body", "Pre-capital demand assessment, distributor reference checks, and local tracking deployment.",
    "Évaluation de la demande avant capital, vérifications de références distributeurs et déploiement du suivi local.",
    "Nachfragebewertung vor Kapitaleinsatz, Referenzprüfungen von Vertriebspartnern und lokales Tracking.",
    "Vraagbeoordeling vóór kapitaal, referentiechecks van distributeurs en lokale tracking.")

# Ghana
add("gh.title", "Ghana Market Entry Case Study: Construction Platform | P23 Africa",
    "Étude de cas d’entrée au Ghana : plateforme de construction | P23 Africa",
    "Fallstudie Markteintritt Ghana: Bauplattform | P23 Africa",
    "Casestudy markttoegang Ghana: bouwplatform | P23 Africa")
add("gh.meta", "From multi-market testing across Ghana, Nigeria & Botswana to 5,000 contractors and 2,150 renters in six months. Discover how P23 built a functioning marketplace in Ghana.",
    "Du test multi-marchés au Ghana, au Nigeria et au Botswana à 5 000 entrepreneurs et 2 150 locataires en six mois. Découvrez comment P23 a construit une place de marché opérationnelle au Ghana.",
    "Vom Mehrländetest in Ghana, Nigeria und Botswana zu 5.000 Auftragnehmern und 2.150 Mietern in sechs Monaten. Erfahren Sie, wie P23 einen funktionierenden Marktplatz in Ghana aufgebaut hat.",
    "Van tests in Ghana, Nigeria en Botswana naar 5.000 aannemers en 2.150 huurders in zes maanden. Ontdek hoe P23 een werkende marktplaats in Ghana bouwde.")
add("gh.keywords", "Ghana market entry, Ghana tech marketplace, Accra construction equipment, commercial expansion Ghana, market validation Africa",
    "entrée marché Ghana, place de marché tech Ghana, équipement de construction Accra, expansion commerciale Ghana, validation de marché Afrique",
    "Markteintritt Ghana, Tech-Marktplatz Ghana, Baugeräte Accra, kommerzielle Expansion Ghana, Marktvalidierung Afrika",
    "markttoegang Ghana, tech-marktplaats Ghana, bouwmaterieel Accra, commerciële expansie Ghana, marktvalidatie Afrika")
add("gh.og_title", "Ghana Case Study: From Testing to 5,000 Contractors in 6 Months | P23 Africa",
    "Étude de cas Ghana : du test à 5 000 entrepreneurs en 6 mois | P23 Africa",
    "Fallstudie Ghana: Vom Test zu 5.000 Auftragnehmern in 6 Monaten | P23 Africa",
    "Casestudy Ghana: van test naar 5.000 aannemers in 6 maanden | P23 Africa")
add("gh.og_desc", "How P23 Africa validated and scaled a digital marketplace in Ghana, onboarding 2,000+ vendors and 5,000+ contractors.",
    "Comment P23 Africa a validé et développé une place de marché numérique au Ghana, avec plus de 2 000 fournisseurs et 5 000 entrepreneurs.",
    "Wie P23 Africa einen digitalen Marktplatz in Ghana validiert und skaliert hat, mit über 2.000 Anbietern und 5.000 Auftragnehmern.",
    "Hoe P23 Africa een digitale marktplaats in Ghana valideerde en opschaalde, met meer dan 2.000 aanbieders en 5.000 aannemers.")
add("gh.crumb", "Ghana Platform Case Study", "Étude de cas plateforme Ghana", "Plattform-Fallstudie Ghana", "Platformcasestudy Ghana")
add("gh.vs", "Ghana (vs Nigeria & Botswana)", "Ghana (face au Nigeria et au Botswana)", "Ghana (gegenüber Nigeria und Botswana)", "Ghana (tegenover Nigeria en Botswana)")
add("gh.sector", "Construction & Equipment Marketplace", "Place de marché construction et équipement", "Marktplatz für Bau und Geräte", "Marktplaats voor bouw en materieel")
add("gh.tag", "6 Months Post-Launch Scale", "Échelle six mois après le lancement", "Skalierung sechs Monate nach dem Start", "Schaal zes maanden na de lancering")
add("gh.hero", "From market testing to 5,000 contractors and 2,150 renters in six months.",
    "Du test de marché à 5 000 entrepreneurs et 2 150 locataires en six mois.",
    "Vom Markttest zu 5.000 Auftragnehmern und 2.150 Mietern in sechs Monaten.",
    "Van markttest naar 5.000 aannemers en 2.150 huurders in zes maanden.")
add("gh.hero_sub", "Entering a new market is one challenge. Building a functioning business in that market is another. When a South African construction company developed a digital platform connecting construction contractors with equipment providers, it wanted to understand where the concept had the strongest potential before committing to a full market launch.",
    "Entrer sur un nouveau marché est un défi. Y construire une activité qui fonctionne en est un autre. Lorsqu’une entreprise sud-africaine de construction a développé une plateforme reliant les entrepreneurs aux fournisseurs d’équipements, elle voulait savoir où le concept avait le plus fort potentiel avant un lancement complet.",
    "Einen neuen Markt zu betreten ist eine Herausforderung. Dort ein funktionierendes Geschäft aufzubauen ist eine andere. Als ein südafrikanisches Bauunternehmen eine digitale Plattform zwischen Auftragnehmern und Geräteanbietern entwickelte, wollte es wissen, wo das Konzept das stärkste Potenzial hatte, bevor es vollständig startete.",
    "Een nieuwe markt betreden is één uitdaging. Er een werkend bedrijf bouwen is een andere. Toen een Zuid-Afrikaans bouwbedrijf een digitaal platform ontwikkelde tussen aannemers en materieelverhuurders, wilde het weten waar het concept de sterkste potentie had vóór een volledige lancering.")
add("gh.hero_2", "P23 Africa helped test the opportunity across Ghana, Nigeria and Botswana. Ghana emerged as the strongest market. From there, we turned the opportunity into an operating business.",
    "P23 Africa a aidé à tester l’opportunité au Ghana, au Nigeria et au Botswana. Le Ghana s’est révélé le marché le plus fort. De là, nous avons transformé l’opportunité en activité opérationnelle.",
    "P23 Africa half, die Chance in Ghana, Nigeria und Botswana zu testen. Ghana erwies sich als stärkster Markt. Von dort haben wir die Chance in ein laufendes Geschäft verwandelt.",
    "P23 Africa hielp de kans te testen in Ghana, Nigeria en Botswana. Ghana bleek de sterkste markt. Van daaruit maakten we de kans tot een werkend bedrijf.")
add("gh.vendors", "Equipment Vendors Onboarded", "Fournisseurs d’équipements intégrés", "Aufgenommene Geräteanbieter", "Aangesloten materieelverhuurders")
add("gh.contractors", "Contractors Onboarded", "Entrepreneurs intégrés", "Aufgenommene Auftragnehmer", "Aangesloten aannemers")
add("gh.renters", "Contractors Actively Rented", "Entrepreneurs ayant réellement loué", "Auftragnehmer mit aktiver Miete", "Aannemers die actief huurden")
add("gh.phase1", "Phase 01 // Multi-Market Validation", "Phase 01 // Validation multi-marchés", "Phase 01 // Validierung mehrerer Märkte", "Fase 01 // Validatie van meerdere markten")
add("gh.which", "First, we tested the markets: Which market should we enter?",
    "D’abord, nous avons testé les marchés : lequel fallait-il choisir ?",
    "Zuerst haben wir die Märkte getestet: Welchen sollten wir betreten?",
    "Eerst testten we de markten: welke moesten we betreden?")
add("gh.which_body", "The client had developed a platform with the potential to transform how construction contractors access equipment. But before investing heavily in launching across Africa, there was an important question to answer:",
    "Le client avait développé une plateforme capable de transformer l’accès des entrepreneurs à l’équipement. Avant d’investir lourdement dans un lancement à l’échelle de l’Afrique, une question importante se posait :",
    "Der Kunde hatte eine Plattform entwickelt, die den Zugang von Auftragnehmern zu Geräten verändern konnte. Bevor stark in einen Launch über Afrika investiert wurde, gab es eine wichtige Frage:",
    "De klant had een platform ontwikkeld dat de toegang van aannemers tot materieel kon veranderen. Voordat zwaar werd geïnvesteerd in een lancering door Afrika, was er een belangrijke vraag:")
add("gh.core", "Strategic Core Question:", "Question stratégique centrale :", "Strategische Kernfrage:", "Strategische kernvraag:")
add("gh.where", "“Where should we launch first?”", "« Où lancer en premier ? »", "„Wo sollten wir zuerst starten?“", "„Waar moeten we eerst lanceren?“")
add("gh.corridors", "P23 helped test demand across three dynamic target corridors: <strong>Ghana | Nigeria | Botswana</strong>.",
    "P23 a aidé à tester la demande sur trois corridors : <strong>Ghana | Nigeria | Botswana</strong>.",
    "P23 half, die Nachfrage in drei Korridoren zu testen: <strong>Ghana | Nigeria | Botswana</strong>.",
    "P23 hielp de vraag te testen in drie corridors: <strong>Ghana | Nigeria | Botswana</strong>.",
    html=True)
add("gh.emerged", "Rather than relying on assumptions about which market looked most attractive, we tested the proposition directly with the market. We engaged potential users, equipment providers and evaluated the commercial response to the platform. <strong>Ghana emerged as the strongest initial potential</strong>, giving the client a clear, data-driven basis for deciding where to focus its capital and talent.",
    "Plutôt que de supposer quel marché semblait le plus attractif, nous avons testé la proposition directement. Nous avons contacté des utilisateurs potentiels et des fournisseurs d’équipements, puis évalué la réponse commerciale. <strong>Le Ghana est apparu comme le plus fort potentiel initial</strong>, donnant au client une base claire, fondée sur les données, pour décider où concentrer capital et talents.",
    "Statt anzunehmen, welcher Markt am attraktivsten wirkte, haben wir das Angebot direkt getestet. Wir haben mögliche Nutzer und Geräteanbieter angesprochen und die kommerzielle Reaktion bewertet. <strong>Ghana zeigte das stärkste Anfangspotenzial</strong> und gab dem Kunden eine klare, datenbasierte Grundlage, wo Kapital und Talent konzentriert werden sollten.",
    "In plaats van aan te nemen welke markt het aantrekkelijkst leek, testten we het voorstel rechtstreeks. We benaderden mogelijke gebruikers en materieelverhuurders en beoordeelden de commerciële reactie. <strong>Ghana bleek de sterkste startpotentie</strong> en gaf de klant een duidelijke, datagedreven basis om te beslissen waar kapitaal en talent naartoe moesten.",
    html=True)
add("gh.phase2", "Phase 02 // Launch Architecture", "Phase 02 // Architecture du lancement", "Phase 02 // Launch-Architektur", "Fase 02 // Architectuur van de lancering")
add("gh.build", "Then we helped build the business: From validation to launch",
    "Ensuite, nous avons aidé à construire l’activité : de la validation au lancement",
    "Dann halfen wir, das Geschäft aufzubauen: von der Validierung zum Start",
    "Daarna hielpen we het bedrijf bouwen: van validatie naar lancering")
add("gh.build_lead", "Once Ghana had been identified as the priority market, the work shifted from <em>“Is there an opportunity?”</em> to <em>“How do we build it?”</em> P23 worked with local partners to establish the operation and support the client through early-stage launch:",
    "Une fois le Ghana choisi comme marché prioritaire, le travail est passé de <em>« Y a-t-il une opportunité ? »</em> à <em>« Comment la construire ? »</em> P23 a travaillé avec des partenaires locaux pour établir l’opération et accompagner le lancement :",
    "Nachdem Ghana als Prioritätsmarkt feststand, wechselte die Arbeit von <em>„Gibt es eine Chance?“</em> zu <em>„Wie bauen wir sie?“</em> P23 arbeitete mit lokalen Partnern am Aufbau und begleitete den frühen Start:",
    "Toen Ghana de prioriteitsmarkt was, verschoof het werk van <em>„Is er een kans?“</em> naar <em>„Hoe bouwen we die?“</em> P23 werkte met lokale partners om de operatie op te zetten en de vroege lancering te begeleiden:",
    html=True)
add("gh.b1", "Establish the local Ghana operation", "Établir l’opération locale au Ghana", "Die lokale Tätigkeit in Ghana aufbauen", "De lokale operatie in Ghana opzetten")
add("gh.b2", "Build the initial frontline team", "Constituer l’équipe terrain initiale", "Das erste Feldteam aufbauen", "Het eerste veldteam bouwen")
add("gh.b3", "Develop the go-to-market approach", "Définir l’approche de mise sur le marché", "Den Markteintrittsansatz entwickeln", "De go-to-market-aanpak ontwikkelen")
add("gh.b4", "Identify priority customer segments", "Identifier les segments clients prioritaires", "Prioritäre Kundensegmente bestimmen", "Prioritaire klantsegmenten bepalen")
add("gh.b5", "Build the qualified prospect pipeline", "Construire le pipeline de prospects qualifiés", "Die qualifizierte Pipeline aufbauen", "De gekwalificeerde pipeline bouwen")
add("gh.b6", "Launch initial online marketing campaigns", "Lancer les premières campagnes en ligne", "Erste Onlinekampagnen starten", "De eerste online campagnes lanceren")
add("gh.b7", "Launch offline sales & direct contractor visits", "Lancer les ventes hors ligne et les visites directes", "Offline-Vertrieb und direkte Besuche starten", "Offline verkoop en directe bezoeken starten")
add("gh.b8", "Develop equipment vendor partnerships", "Développer des partenariats avec les fournisseurs", "Partnerschaften mit Geräteanbietern entwickeln", "Partnerschappen met materieelverhuurders ontwikkelen")
add("gh.b9", "Acquire verified contractors onto platform", "Acquérir des entrepreneurs vérifiés sur la plateforme", "Geprüfte Auftragnehmer auf die Plattform bringen", "Geverifieerde aannemers op het platform krijgen")
add("gh.b10", "Establish ecosystem value-add partnerships", "Établir des partenariats à valeur ajoutée", "Wertstiftende Ökosystem-Partnerschaften aufbauen", "Waarde-toevoegende partnerschappen opzetten")
add("gh.liquid", "The objective wasn’t simply to launch a website. It was to build a functioning, liquid marketplace.",
    "L’objectif n’était pas simplement de lancer un site. Il s’agissait de construire une place de marché qui fonctionne et qui a de la liquidité.",
    "Das Ziel war nicht nur ein Website-Start. Es ging um einen funktionierenden, liquiden Marktplatz.",
    "Het doel was niet alleen een website lanceren. Het was een werkende, liquide marktplaats bouwen.")
add("gh.liquidity", "Liquidity & Supply-Demand", "Liquidité et offre-demande", "Liquidität und Angebot-Nachfrage", "Liquiditeit en vraag-aanbod")
add("gh.both", "Building both sides of the marketplace", "Construire les deux côtés de la place de marché", "Beide Seiten des Marktplatzes aufbauen", "Beide kanten van de marktplaats bouwen")
add("gh.both_lead", "Vendors and contractors had to grow together. A platform like this only works when there is active liquidity on both sides: contractors need access to machinery, while equipment owners need contractors ready to rent.",
    "Fournisseurs et entrepreneurs devaient croître ensemble. Une telle plateforme ne fonctionne que s’il y a de la liquidité des deux côtés : les entrepreneurs ont besoin de machines, et les propriétaires d’équipements ont besoin d’entrepreneurs prêts à louer.",
    "Anbieter und Auftragnehmer mussten gemeinsam wachsen. Eine solche Plattform funktioniert nur mit Liquidität auf beiden Seiten: Auftragnehmer brauchen Maschinen, Gerätebesitzer brauchen Auftragnehmer, die mieten wollen.",
    "Aanbieders en aannemers moesten samen groeien. Zo’n platform werkt alleen met liquiditeit aan beide kanten: aannemers hebben machines nodig, en eigenaren hebben aannemers nodig die willen huren.")
add("gh.both_body", "That meant P23’s market development activity had to operate simultaneously across both audiences. Our prospecting and tracking software provided live visibility over activity:",
    "L’activité de développement de marché de P23 devait donc couvrir les deux audiences en même temps. Notre logiciel de prospection et de suivi donnait une visibilité en direct :",
    "Die Marktentwicklung von P23 musste daher beide Zielgruppen gleichzeitig bedienen. Unsere Prospecting- und Tracking-Software gab Live-Sicht auf die Aktivität:",
    "De marktontwikkeling van P23 moest dus beide doelgroepen tegelijk bedienen. Onze prospectie- en trackingsoftware gaf live zicht op de activiteit:")
add("gh.h1", "Targeting & Pipeline", "Ciblage et pipeline", "Ansprache und Pipeline", "Targeting en pipeline")
add("gh.h1b", "Who was being targeted and contacted across Accra and mining/industrial belts.",
    "Qui était ciblé et contacté à Accra et dans les zones minières et industrielles.",
    "Wer in Accra und in den Bergbau- und Industriekorridoren angesprochen wurde.",
    "Wie werd benaderd in Accra en in de mijnbouw- en industriegebieden.")
add("gh.h2", "Relationship Stage", "Stade de la relation", "Beziehungsstufe", "Relatiefase")
add("gh.h2b", "What stage each vendor relationship was at, from sign-up to fleet listing.",
    "À quel stade en était chaque relation fournisseur, de l’inscription à la mise en ligne de la flotte.",
    "In welcher Stufe jede Anbieterbeziehung war, von der Anmeldung bis zur Flottenlistung.",
    "In welke fase elke aanbiedersrelatie zat, van aanmelding tot vlootlisting.")
add("gh.h3", "Performance Feedback", "Retour de performance", "Leistungsfeedback", "Prestatiefeedback")
add("gh.h3b", "Which marketing messages and outreach cadences produced verified rentals.",
    "Quels messages et quels rythmes de contact produisaient des locations vérifiées.",
    "Welche Botschaften und Kontaktfrequenzen geprüfte Vermietungen brachten.",
    "Welke boodschappen en contactritmes geverifieerde verhuringen opleverden.")
add("gh.traction", "Traction at 6 Months", "Traction à 6 mois", "Zugkraft nach 6 Monaten", "Tractie na 6 maanden")
add("gh.numbers", "2,000+ vendors. 5,000+ contractors. 2,150+ renters.",
    "Plus de 2 000 fournisseurs. Plus de 5 000 entrepreneurs. Plus de 2 150 locataires.",
    "Über 2.000 Anbieter. Über 5.000 Auftragnehmer. Über 2.150 Mieter.",
    "Meer dan 2.000 aanbieders. Meer dan 5.000 aannemers. Meer dan 2.150 huurders.")
add("gh.eq", "Equipment Vendors", "Fournisseurs d’équipements", "Geräteanbieter", "Materieelverhuurders")
add("gh.eq_b", "Equipment providers joined the platform, significantly expanding the catalog and availability of heavy machinery for contractors.",
    "Des fournisseurs ont rejoint la plateforme, élargissant nettement le catalogue et la disponibilité des machines lourdes pour les entrepreneurs.",
    "Anbieter kamen auf die Plattform und erweiterten Katalog und Verfügbarkeit schwerer Maschinen für Auftragnehmer deutlich.",
    "Aanbieders kwamen op het platform en vergrootten de catalogus en beschikbaarheid van zware machines voor aannemers aanzienlijk.")
add("gh.co", "Contractors Onboarded", "Entrepreneurs intégrés", "Aufgenommene Auftragnehmer", "Aangesloten aannemers")
add("gh.co_b", "The platform built a substantial, highly engaged community of contractors looking for reliable access to equipment.",
    "La plateforme a construit une communauté importante et engagée d’entrepreneurs en quête d’un accès fiable à l’équipement.",
    "Die Plattform baute eine große, engagierte Gemeinschaft von Auftragnehmern auf, die zuverlässigen Gerätezugang suchten.",
    "Het platform bouwde een grote, betrokken gemeenschap van aannemers die betrouwbare toegang tot materieel zochten.")
add("gh.ar", "Active Renters", "Locataires actifs", "Aktive Mieter", "Actieve huurders")
add("gh.ar_b", "Critically, the platform was not simply collecting empty logins: over 2,150 contractors completed paid rentals, demonstrating meaningful commercial traction.",
    "Surtout, la plateforme ne collectait pas de simples comptes vides : plus de 2 150 entrepreneurs ont réalisé des locations payantes, signe d’une traction commerciale réelle.",
    "Entscheidend: Die Plattform sammelte nicht nur leere Logins. Über 2.150 Auftragnehmer schlossen bezahlte Mieten ab und zeigten echte kommerzielle Zugkraft.",
    "Belangrijk: het platform verzamelde niet alleen lege accounts. Meer dan 2.150 aannemers rondden betaalde verhuringen af en toonden echte commerciële tractie.")
add("gh.agile", "Agile Growth", "Croissance agile", "Agiles Wachstum", "Wendbare groei")
add("gh.opt", "We didn’t stop at launch: Continuous market optimisation",
    "Nous ne nous sommes pas arrêtés au lancement : optimisation continue du marché",
    "Wir haben nicht beim Start aufgehört: kontinuierliche Marktoptimierung",
    "We stopten niet bij de lancering: voortdurende marktoptimalisatie")
add("gh.opt_1", "A successful market launch isn’t a fixed plan. The market tells you what is working, and what isn’t. As the business developed, P23 continued to monitor activity and performance, identifying where the strongest opportunities were emerging.",
    "Un lancement réussi n’est pas un plan figé. Le marché dit ce qui fonctionne et ce qui ne fonctionne pas. À mesure que l’activité se développait, P23 a continué à suivre l’activité et la performance, pour voir où les opportunités les plus fortes apparaissaient.",
    "Ein erfolgreicher Start ist kein fester Plan. Der Markt zeigt, was funktioniert und was nicht. Während das Geschäft wuchs, beobachtete P23 Aktivität und Leistung weiter und erkannte, wo die stärksten Chancen entstanden.",
    "Een geslaagde lancering is geen vast plan. De markt laat zien wat werkt en wat niet. Terwijl het bedrijf groeide, bleef P23 activiteit en prestaties volgen en zien waar de sterkste kansen ontstonden.")
add("gh.opt_2", "We continually refined who was targeted, which messages resonated, where sales teams concentrated, and which commercial partnerships unlocked higher deal value.",
    "Nous avons continuellement affiné qui était ciblé, quels messages résonnaient, où les équipes commerciales se concentraient et quels partenariats augmentaient la valeur des affaires.",
    "Wir haben fortlaufend verfeinert, wen wir ansprachen, welche Botschaften ankamen, wo Vertriebsteams sich konzentrierten und welche Partnerschaften den Geschäftswert erhöhten.",
    "We verfijnen voortdurend wie we benaderden, welke boodschappen aansloegen, waar verkoopteams zich concentreerden en welke partnerschappen de dealwaarde verhoogden.")
add("gh.prop", "Proposition Value", "Valeur de la proposition", "Angebotswert", "Propositiewaarde")
add("gh.part", "Strengthening the proposition through partnerships",
    "Renforcer la proposition par des partenariats",
    "Das Angebot durch Partnerschaften stärken",
    "De propositie versterken met partnerschappen")
add("gh.part_1", "As the platform developed, P23 facilitated strategic partnerships that improved customer confidence. One example was the development of <strong>integrated insurance partnerships</strong>.",
    "À mesure que la plateforme se développait, P23 a facilité des partenariats stratégiques qui ont renforcé la confiance des clients. Un exemple a été le développement de <strong>partenariats d’assurance intégrés</strong>.",
    "Während die Plattform wuchs, hat P23 strategische Partnerschaften ermöglicht, die das Vertrauen der Kunden stärkten. Ein Beispiel waren <strong>integrierte Versicherungspartnerschaften</strong>.",
    "Terwijl het platform groeide, faciliteerde P23 strategische partnerschappen die het vertrouwen van klanten versterkten. Een voorbeeld was de ontwikkeling van <strong>geïntegreerde verzekeringspartnerschappen</strong>.",
    html=True)
add("gh.part_2", "This gave equipment owners and renting contractors peace of mind with broader damage and loss protection, turning equipment hire from a high-friction risk into a trusted digital transaction.",
    "Cela a donné aux propriétaires et aux entrepreneurs locataires une plus grande tranquillité grâce à une protection élargie contre les dommages et les pertes, transformant la location d’un risque à forte friction en une transaction numérique de confiance.",
    "Das gab Gerätebesitzern und mietenden Auftragnehmern mehr Sicherheit durch breiteren Schutz bei Schaden und Verlust und machte die Miete von einem reibungsreichen Risiko zu einer vertrauenswürdigen digitalen Transaktion.",
    "Dat gaf eigenaren en hurende aannemers meer rust door bredere bescherming tegen schade en verlies, en maakte van verhuur een vertrouwde digitale transactie in plaats van een risico met veel wrijving.")
add("gh.exec", "Comprehensive Execution", "Exécution complète", "Umfassende Umsetzung", "Volledige uitvoering")
add("gh.did", "What P23 actually did", "Ce que P23 a réellement fait", "Was P23 tatsächlich getan hat", "Wat P23 werkelijk deed")
add("gh.did_body", "This project demonstrates that market entry doesn’t have to stop at research. P23 supported the client across the entire commercial lifecycle:",
    "Ce projet montre que l’entrée sur un marché ne doit pas s’arrêter à la recherche. P23 a accompagné le client sur tout le cycle commercial :",
    "Dieses Projekt zeigt, dass Markteintritt nicht bei der Recherche enden muss. P23 begleitete den Kunden über den gesamten kommerziellen Zyklus:",
    "Dit project laat zien dat markttoegang niet bij onderzoek hoeft te stoppen. P23 begeleidde de klant over de hele commerciële cyclus:")
add("gh.e1", "01. Validation", "01. Validation", "01. Validierung", "01. Validatie")
add("gh.e1t", "Market Validation", "Validation du marché", "Marktvalidierung", "Marktvalidatie")
add("gh.e1b", "Tested Ghana, Nigeria and Botswana to identify where proposition had strongest potential.",
    "A testé le Ghana, le Nigeria et le Botswana pour voir où la proposition avait le plus fort potentiel.",
    "Hat Ghana, Nigeria und Botswana getestet, um zu sehen, wo das Angebot das stärkste Potenzial hatte.",
    "Testte Ghana, Nigeria en Botswana om te zien waar de propositie de sterkste potentie had.")
add("gh.e2", "02. Setup", "02. Installation", "02. Aufbau", "02. Opzet")
add("gh.e2t", "Local Market Setup", "Mise en place locale", "Lokaler Marktaufbau", "Lokale marktsetup")
add("gh.e2b", "Worked with local partners to establish compliant in-country operation.",
    "A travaillé avec des partenaires locaux pour établir une opération conforme dans le pays.",
    "Hat mit lokalen Partnern eine regelkonforme Tätigkeit im Land aufgebaut.",
    "Werkte met lokale partners om een conforme operatie in het land op te zetten.")
add("gh.e3", "03. Talent", "03. Talents", "03. Talent", "03. Talent")
add("gh.e3t", "Team Development", "Développement de l’équipe", "Teamentwicklung", "Teamontwikkeling")
add("gh.e3b", "Recruited and trained the ground sales & operations team.",
    "A recruté et formé l’équipe commerciale et opérationnelle de terrain.",
    "Hat das Vertriebs- und Betriebsteam vor Ort rekrutiert und geschult.",
    "Wierf en trainde het verkoop- en operationele veldteam.")
add("gh.e4", "04. GTM", "04. Mise sur le marché", "04. Markteintritt", "04. Go-to-market")
add("gh.e4t", "Go-to-Market Campaigns", "Campagnes de mise sur le marché", "Markteintrittskampagnen", "Go-to-marketcampagnes")
add("gh.e4b", "Developed and executed online and offline acquisition funnels.",
    "A conçu et exécuté des tunnels d’acquisition en ligne et hors ligne.",
    "Hat Online- und Offline-Akquisitionstrichter entwickelt und umgesetzt.",
    "Ontwikkelde en voerde online en offline acquisitietrechters uit.")
add("gh.e5", "05. Pipeline", "05. Pipeline", "05. Pipeline", "05. Pipeline")
add("gh.e5t", "Active Prospecting", "Prospection active", "Aktive Ansprache", "Actieve prospectie")
add("gh.e5b", "Identified and prioritised high-volume contractors and equipment yards.",
    "A identifié et priorisé les entrepreneurs à fort volume et les parcs d’équipements.",
    "Hat Auftragnehmer mit hohem Volumen und Gerätehöfe identifiziert und priorisiert.",
    "Vond en prioriteerde aannemers met hoog volume en materieelwerven.")
add("gh.e6", "06. Tech", "06. Technologie", "06. Technik", "06. Techniek")
add("gh.e6t", "Operational Visibility", "Visibilité opérationnelle", "Operative Sicht", "Operationeel zicht")
add("gh.e6b", "Deployed P23 tracking software to maintain transparency over activities.",
    "A déployé le logiciel de suivi P23 pour garder la transparence sur les activités.",
    "Hat die Tracking-Software von P23 eingesetzt, um Aktivitäten transparent zu halten.",
    "Zette de trackingsoftware van P23 in om activiteiten transparant te houden.")
add("gh.e7", "07. Ecosystem", "07. Écosystème", "07. Ökosystem", "07. Ecosysteem")
add("gh.e7t", "Strategic Partnerships", "Partenariats stratégiques", "Strategische Partnerschaften", "Strategische partnerschappen")
add("gh.e7b", "Facilitated equipment insurance partnerships to build trust.",
    "A facilité des partenariats d’assurance équipement pour construire la confiance.",
    "Hat Versicherungspartnerschaften für Geräte ermöglicht, um Vertrauen aufzubauen.",
    "Faciliteerde verzekeringspartnerschappen voor materieel om vertrouwen op te bouwen.")
add("gh.e8", "08. Scale", "08. Échelle", "08. Skalierung", "08. Schaal")
add("gh.e8t", "Market Optimisation", "Optimisation du marché", "Marktoptimierung", "Marktoptimalisatie")
add("gh.e8b", "Used live performance data to continually focus resources on winning channels.",
    "A utilisé les données de performance en direct pour concentrer les ressources sur les canaux gagnants.",
    "Hat Live-Leistungsdaten genutzt, um Ressourcen fortlaufend auf erfolgreiche Kanäle zu lenken.",
    "Gebruikte live prestatiedata om middelen voortdurend op winnende kanalen te richten.")
add("gh.approach", "From market testing to market growth. This is the P23 approach.",
    "Du test de marché à la croissance. Voici l’approche P23.",
    "Vom Markttest zum Marktwachstum. Das ist der Ansatz von P23.",
    "Van markttest naar marktgroei. Dit is de aanpak van P23.")
add("gh.approach_body", "Understand. Validate. Connect. Execute. Entering a market is only the beginning. Building a functioning, profitable business there is the real objective.",
    "Comprendre. Valider. Relier. Exécuter. Entrer sur un marché n’est qu’un début. Y construire une activité rentable qui fonctionne est le véritable objectif.",
    "Verstehen. Prüfen. Verbinden. Umsetzen. Der Markteintritt ist nur der Anfang. Dort ein funktionierendes, profitables Geschäft aufzubauen ist das eigentliche Ziel.",
    "Begrijpen. Toetsen. Verbinden. Uitvoeren. Een markt betreden is pas het begin. Er een werkend, winstgevend bedrijf bouwen is het echte doel.")
add("gh.sibling", "Sibling Case Study Dossier", "Dossier lié", "Verwandtes Falldossier", "Verwant casusdossier")
add("gh.sib_title", "Nigeria: HVAC Manufacturer", "Nigeria : fabricant de CVC", "Nigeria: HLK-Hersteller", "Nigeria: HVAC-fabrikant")
add("gh.sib_body", "Pre-capital demand testing, 10-point distributor due diligence, and ground tracking software.",
    "Test de demande avant capital, diligence distributeur en 10 points et logiciel de suivi terrain.",
    "Nachfragetest vor Kapitaleinsatz, 10-Punkte-Prüfung der Vertriebspartner und Tracking vor Ort.",
    "Vraagtest vóór kapitaal, distributeurstoetsing in 10 punten en trackingsoftware ter plaatse.")
add("gh.sib_link", "Explore Nigeria Case Study", "Voir l’étude de cas Nigeria", "Nigeria-Fallstudie ansehen", "Nigeria-casestudy bekijken")
add("gh.accel", "Ghana Market Acceleration", "Accélération du marché ghanéen", "Marktbeschleunigung Ghana", "Marktversnelling Ghana")
add("gh.looking", "Looking to enter Ghana?", "Vous envisagez le Ghana ?", "Ghana im Blick?", "Denkt u aan Ghana?")
add("gh.cta", "If you’re considering Ghana, or trying to determine which African market should come first, P23 can help you test the opportunity, build the operation and develop the route to growth.",
    "Si vous envisagez le Ghana, ou si vous cherchez quel marché africain doit venir en premier, P23 peut vous aider à tester l’opportunité, construire l’opération et développer la voie de croissance.",
    "Wenn Sie Ghana prüfen oder entscheiden wollen, welcher afrikanische Markt zuerst kommt, kann P23 helfen, die Chance zu testen, den Betrieb aufzubauen und den Wachstumsweg zu entwickeln.",
    "Als u Ghana overweegt of wilt bepalen welke Afrikaanse markt eerst komt, kan P23 helpen de kans te testen, de operatie te bouwen en de groeiroute te ontwikkelen.")
add("gh.talk", "Start a conversation with P23 Africa", "Démarrer une conversation avec P23 Africa", "Gespräch mit P23 Africa starten", "Een gesprek met P23 Africa starten")
add("gh.explore", "Explore Market Entry & Expansion", "Voir l’entrée et l’expansion de marché", "Markteintritt und Expansion ansehen", "Markttoegang en expansie bekijken")
add("gh.hub", "Accra Commercial Hub", "Pôle commercial d’Accra", "Kommerzielles Zentrum Accra", "Commercieel knooppunt Accra")
add("gh.hub_body", "Testing, team setup, marketplace liquidity, and corporate partnerships across Ghana.",
    "Tests, mise en place de l’équipe, liquidité de la place de marché et partenariats au Ghana.",
    "Tests, Teamaufbau, Marktplatzliquidität und Unternehmenspartnerschaften in Ghana.",
    "Tests, teamopzet, marktplaatsliquiditeit en partnerschappen in Ghana.")


def php_quote(value: str) -> str:
    value = value.replace("'", "’")
    return "'" + value + "'"


def emit_php(lang_index: int) -> str:
    tree = {}
    for key, _src, en, fr, de, nl, _html in ITEMS:
        group, name = key.split(".", 1)
        tree.setdefault(group, {})[name] = (en, fr, de, nl)[lang_index]
    lines = ["<?php", "", "return ["]
    for group, fields in tree.items():
        lines.append(f"    '{group}' => [")
        for name, value in fields.items():
            lines.append(f"        '{name}' => {php_quote(value)},")
        lines.append("    ],")
    lines.append("];")
    lines.append("")
    return "\n".join(lines)


def flex(text, source, repl, label):
    parts = source.split()
    pattern = r"\s+".join(re.escape(part) for part in parts)
    text2, n = re.subn(pattern, lambda _m: repl, text, count=1)
    if n == 0:
        print("MISS", label)
    else:
        print(f"OK {label}")
    return text2


def wire(path: Path, prefix: str):
    raw = path.read_bytes()
    nl = "\r\n" if b"\r\n" in raw else "\n"
    text = raw.decode("utf-8").replace("\r\n", "\n")
    text = text.replace("{{ route('homepage') }}", "{{ locale_route('homepage') }}")
    text = text.replace("{{ url('services') }}", "{{ locale_route('services') }}")
    text = text.replace("{{ route('services.market-entry') }}", "{{ locale_route('services.market-entry') }}")
    text = text.replace("{{ url('contact') }}", "{{ locale_route('contact') }}")
    text = text.replace("{{ route('services.market-entry.ghana') }}", "{{ locale_route('services.market-entry.ghana') }}")
    text = text.replace("{{ route('services.market-entry.nigeria') }}", "{{ locale_route('services.market-entry.nigeria') }}")
    text = text.replace(">Home</a>", ">{{ __('nav.home') }}</a>")
    text = text.replace(">Services</a>", ">{{ __('nav.services') }}</a>")
    text = text.replace("Market Entry &amp; Expansion", "{{ __('services.market_entry') }}")
    scoped = [item for item in ITEMS if item[0].startswith(prefix + ".")]
    scoped.sort(key=lambda item: len(item[1]), reverse=True)
    for key, source, _en, _fr, _de, _nl, html in scoped:
        blade = "{!! __('market_country." + key + "') !!}" if html else "{{ __('market_country." + key + "') }}"
        text = flex(text, source, blade, key)
    path.write_bytes(text.replace("\n", nl).encode("utf-8"))


def main():
    langs = {"en": 0, "fr": 1, "de": 2, "nl": 3}
    for code, index in langs.items():
        target = ROOT / f"lang/{code}/market_country.php"
        target.write_text(emit_php(index), encoding="utf-8", newline="\n")
        print("wrote", target.name, code)
    wire(ROOT / "resources/views/market-entry/nigeria.blade.php", "ng")
    wire(ROOT / "resources/views/market-entry/ghana.blade.php", "gh")


if __name__ == "__main__":
    main()
