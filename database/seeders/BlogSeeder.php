<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@p23africa.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Get or create categories
        $categories = [];
        $categoryNames = ['Market Analysis', 'Business Strategy', 'Expansion Guide', 'Industry Report', 'Case Study'];

        foreach ($categoryNames as $categoryName) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
            $categories[] = $category->id;
        }

        // Blog data with some having PDFs
        $blogs = [
            [
                'title' => 'Unlocking Business Opportunities in East Africa',
                'subtitle' => 'A comprehensive guide to navigating the East African market',
                'content_1' => '<p>East Africa has emerged as one of the fastest-growing economic regions in the world. With a combined GDP growth rate averaging 5-6% annually, the region presents unprecedented opportunities for businesses looking to expand into new markets.</p><p>The East African Community (EAC) comprising Kenya, Tanzania, Uganda, Rwanda, Burundi, South Sudan, and the Democratic Republic of Congo, offers a market of over 300 million people. This demographic advantage, combined with improving infrastructure and digital penetration, makes it an attractive destination for investment.</p>',
                'content_2' => '',
                'image_path' => null,
                'pdf_path' => 'pdfs/east-africa-report.pdf',
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'Digital Transformation in African Markets',
                'subtitle' => 'How technology is reshaping business across the continent',
                'content_1' => '<p>Africa is experiencing a digital revolution that is transforming how businesses operate and engage with customers. With mobile phone penetration exceeding 80% in many countries, digital channels have become the primary means of commerce and communication.</p><p>The COVID-19 pandemic accelerated this transformation, forcing businesses to adapt quickly to digital-first strategies. E-commerce, digital payments, and online services saw exponential growth, with many companies reporting increases of over 300% in digital transactions.</p>',
                'content_2' => '<p>Key trends include the rise of fintech solutions, growth in e-commerce platforms, expansion of digital payment systems, adoption of cloud-based business solutions, and increased focus on cybersecurity. Companies like Jumia, Flutterwave, and Paystack are leading the charge in their respective sectors.</p><p>For businesses looking to succeed in this environment, investing in digital infrastructure is crucial. This includes developing mobile-first platforms, integrating digital payment systems, leveraging data analytics, and building strong online presence. The future of African business is undeniably digital.</p>',
                'image_path' => 'blogs/digital-transformation.jpg',
                'pdf_path' => null,
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'Navigating Regulatory Frameworks in West Africa',
                'subtitle' => 'Understanding compliance requirements for successful market entry',
                'content_1' => '<p>West Africa presents significant opportunities for business expansion, but success requires thorough understanding of the regulatory landscape. The Economic Community of West African States (ECOWAS) has made strides in harmonizing regulations, yet country-specific requirements remain complex.</p><p>Nigeria, as the largest economy in the region, has unique regulatory requirements including company registration with the Corporate Affairs Commission, obtaining necessary licenses and permits, tax registration with the Federal Inland Revenue Service, and compliance with labor laws and employment regulations.</p>',
                'content_2' => '<p>Ghana offers a more streamlined process through its Ghana Investment Promotion Centre (GIPC), which provides a one-stop-shop for investors. The country has consistently ranked as one of the most business-friendly in Africa. Senegal has also implemented reforms to attract foreign investment, particularly in infrastructure and energy sectors.</p><p>Key considerations for businesses include understanding sector-specific regulations, engaging local legal and tax advisors, planning for adequate capitalization requirements, and developing strong government relations. While the regulatory environment can be challenging, proper preparation and local expertise can significantly smooth the entry process.</p>',
                'image_path' => 'blogs/west-africa-regulatory.jpg',
                'pdf_path' => null,
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'Southern Africa Investment Climate Report 2025',
                'subtitle' => 'Opportunities and challenges in the SADC region',
                'content_1' => '<p>The Southern African Development Community (SADC) region continues to attract significant foreign direct investment, with South Africa, Botswana, and Mauritius leading the way. This comprehensive report analyzes investment trends, sectoral opportunities, and risk factors across the region.</p><p>South Africa remains the economic powerhouse, contributing over 60% of the region\'s GDP. However, countries like Mozambique with its vast natural gas reserves, Zambia with copper mining, and Zimbabwe with agriculture present compelling investment cases.</p>',
                'content_2' => '',
                'image_path' => null,
                'pdf_path' => 'pdfs/southern-africa-investment-2025.pdf',
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'Building Strategic Partnerships in African Markets',
                'subtitle' => 'The key to sustainable business growth',
                'content_1' => '<p>Success in African markets often hinges on the ability to build and maintain strong strategic partnerships. Unlike many Western markets where transactions can be purely commercial, African business culture places significant emphasis on relationships and trust.</p><p>Local partnerships provide crucial advantages including market knowledge and cultural understanding, established distribution networks, government and regulatory connections, and shared risk in new markets. Companies that have succeeded in Africa consistently cite partnerships as a critical success factor.</p>',
                'content_2' => '<p>When selecting partners, consider factors such as track record and reputation, financial stability, aligned values and vision, complementary capabilities, and commitment to long-term success. Due diligence is essential, including background checks, reference verification, and legal review.</p><p>Successful partnerships require clear communication, defined roles and responsibilities, fair profit-sharing arrangements, regular performance reviews, and flexibility to adapt to changing conditions. Investing time in building relationships before formalizing partnerships pays dividends. Companies should attend industry events, engage with business associations, and spend time in-market to build networks.</p>',
                'image_path' => 'blogs/strategic-partnerships.jpg',
                'pdf_path' => null,
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'Renewable Energy Opportunities in Sub-Saharan Africa',
                'subtitle' => 'Powering the future of the continent',
                'content_1' => '<p>Sub-Saharan Africa faces a significant energy deficit, with over 600 million people lacking access to electricity. However, this challenge presents an enormous opportunity for renewable energy development. The region has abundant solar, wind, hydro, and geothermal resources that remain largely untapped.</p><p>Solar energy potential is particularly promising, with most of the region receiving over 2,000 hours of sunshine annually. Countries like South Africa, Kenya, and Morocco have already made significant investments in solar infrastructure, demonstrating the viability of large-scale projects.</p>',
                'content_2' => '',
                'image_path' => null,
                'pdf_path' => 'pdfs/renewable-energy-africa-2025.pdf',
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'Supply Chain Management in African Operations',
                'subtitle' => 'Overcoming logistical challenges for business success',
                'content_1' => '<p>Effective supply chain management is critical for business success in Africa, yet it remains one of the most challenging aspects of operations. Infrastructure limitations, border delays, and fragmented logistics networks require innovative approaches and careful planning.</p><p>The continent\'s vast size and diverse terrain create unique challenges. Road networks vary significantly in quality, with many rural areas accessible only during dry seasons. Port congestion and inefficient customs procedures can add weeks to delivery times.</p>',
                'content_2' => '<p>Successful companies employ strategies including diversifying transportation modes, building inventory buffers, partnering with reliable local logistics providers, investing in tracking and visibility systems, and developing contingency plans for disruptions. Technology is playing an increasing role, with companies using GPS tracking, route optimization software, and digital documentation to improve efficiency.</p><p>The African Continental Free Trade Area (AfCFTA) promises to streamline cross-border trade, though implementation is gradual. Regional economic communities are working to harmonize standards and reduce barriers. Companies that master supply chain management gain significant competitive advantage in African markets.</p>',
                'image_path' => 'blogs/supply-chain-africa.jpg',
                'pdf_path' => null,
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'The Rise of African Tech Startups',
                'subtitle' => 'Innovation hubs driving economic transformation',
                'content_1' => '<p>Africa\'s tech startup ecosystem has exploded in recent years, with venture capital funding reaching record levels. Cities like Lagos, Nairobi, Cape Town, and Cairo have emerged as major tech hubs, attracting global investors and spawning unicorns like Flutterwave, Andela, and Wave.</p><p>The sector\'s growth is driven by several factors including young, tech-savvy population, increasing internet and smartphone penetration, urgent need for innovative solutions to local challenges, and supportive government policies in key countries.</p>',
                'content_2' => '<p>Fintech dominates, accounting for over 30% of funding, but other sectors are growing rapidly including e-commerce, healthtech, edtech, agritech, and logistics. Incubators and accelerators like Y Combinator, 500 Startups, and local programs provide crucial support for early-stage startups.</p><p>Challenges remain, including access to late-stage funding, talent retention, regulatory uncertainty, and infrastructure limitations. However, the trend is clear: African tech startups are not just solving local problems but creating solutions with global potential. The next decade will likely see multiple African tech companies achieving billion-dollar valuations and expanding beyond the continent.</p>',
                'image_path' => 'blogs/african-tech-startups.jpg',
                'pdf_path' => null,
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'Agriculture and Agribusiness Investment Guide',
                'subtitle' => 'Feeding Africa and the world',
                'content_1' => '<p>Agriculture remains the backbone of African economies, employing over 60% of the workforce and contributing significantly to GDP. Yet the sector operates well below its potential, with yields far lower than global averages. This gap represents a massive opportunity for investment and innovation.</p><p>The continent has 60% of the world\'s uncultivated arable land, abundant water resources, diverse climates suitable for various crops, and growing demand from a young, expanding population. Countries like Ethiopia, Nigeria, Kenya, and Ghana are prioritizing agricultural development.</p>',
                'content_2' => '',
                'image_path' => null,
                'pdf_path' => 'pdfs/agriculture-investment-guide-africa.pdf',
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
            [
                'title' => 'Understanding African Consumer Markets',
                'subtitle' => 'Tapping into a rising middle class',
                'content_1' => '<p>Africa\'s consumer market is undergoing dramatic transformation. With a population of 1.4 billion that\'s expected to double by 2050, and a rapidly growing middle class, the continent represents one of the world\'s most promising consumer markets.</p><p>Consumer spending is projected to reach $2.5 trillion by 2030, driven by urbanization, rising incomes, and demographic shifts. Young consumers, who make up over 60% of the population, are digital-savvy and brand-conscious, with different preferences than previous generations.</p>',
                'content_2' => '<p>Key sectors seeing growth include fast-moving consumer goods, telecommunications and digital services, banking and financial services, retail and e-commerce, entertainment and media, and healthcare and wellness. International brands are increasingly tailoring products for African markets rather than simply exporting Western offerings.</p><p>Success requires understanding regional and cultural differences. A strategy that works in Nigeria may not work in Kenya. Consumer preferences vary significantly by income level, urban vs rural location, and age demographic. Companies must invest in market research, develop appropriate distribution strategies, and build brands that resonate with local consumers. Those who do will be well-positioned to capture share in this high-growth market.</p>',
                'image_path' => 'blogs/african-consumer-markets.jpg',
                'pdf_path' => null,
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $user->id,
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }

        $this->command->info('Successfully seeded 10 blogs (4 with PDFs, 6 without PDFs)');
    }
}
