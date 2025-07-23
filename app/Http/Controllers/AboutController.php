<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
  public function index(): Response
  {
    $subscriberCount = Subscriber::where('status', 'subscribed')->count();

    return Inertia::render('About', [
      'values' => $this->getValues(),
      'team' => $this->getTeamMembers(),
      'timeline' => $this->getTimeline(),
      'partners' => $this->getPartners(),
      'subscriberCount' => $subscriberCount,
    ]);
  }

  private function getValues(): array
  {
    return [
      [
        'icon' => 'scale',
        'title' => 'Integrity',
        'description' => 'We maintain high ethical standards and transparency in all our operations and relationships.',
      ],
      [
        'icon' => 'users',
        'title' => 'Community Focus',
        'description' => 'We prioritize the needs and voices of local communities in all our initiatives.',
      ],
      [
        'icon' => 'light-bulb',
        'title' => 'Innovation',
        'description' => 'We embrace creative solutions and new approaches to address development challenges.',
      ],
      [
        'icon' => 'hand',
        'title' => 'Accountability',
        'description' => 'We take responsibility for our actions and maintain transparency with our stakeholders.',
      ],
    ];
  }

  private function getTeamMembers(): array
  {
    return [
      [
        'name' => 'Linda Harawa',
        'position' => 'Chairperson',
        'image' => 'images/team/linda-harawa.jpg',
        'bio' => 'Over 15 years of experience in programme development, youth leadership, and child protection.',
        'linkedin' => 'https://linkedin.com/in/grace-banda',
        'twitter' => 'https://twitter.com/drgraceb'
      ],
      [
        'name' => 'John Chipeta',
        'position' => 'Vice Chairperson',
        'image' => 'images/team/john-chipeta.jpg',
        'bio' => 'Public policy expert with a focus on governance, leadership, and human rights.',
        'linkedin' => 'https://linkedin.com/in/grace-banda',
        'twitter' => 'https://twitter.com/drgraceb'
      ],
      [
        'name' => 'Stella Nkhonya Chisangwala',
        'position' => 'Board Member',
        'image' => 'images/team/stella-nkhonya.jpg',
        'bio' => 'Champion for disability rights and empowerment of women and girls with disabilities.',
        'linkedin' => 'https://linkedin.com/in/grace-banda',
        'twitter' => 'https://twitter.com/drgraceb'
      ],
      [
        'name' => 'Alemekezeke Chitanje',
        'position' => 'Board Member',
        'image' => 'images/team/alemekezeke-chitanje.jpg',
        'bio' => 'Lecturer in Teachers Education with a passion for philanthropic work and supporting vulnerable groups.',
        'linkedin' => 'https://linkedin.com/in/grace-banda',
        'twitter' => 'https://twitter.com/drgraceb'
      ],
      [
        'name' => 'Cyprian Chimbinya',
        'position' => 'Board Member',
        'image' => 'images/team/cyprian-chimbinya.jpg',
        'bio' => 'Governance and advocacy expert with extensive experience in community development and project management.',
        'linkedin' => 'https://linkedin.com/in/grace-banda',
        'twitter' => 'https://twitter.com/drgraceb'
      ],
      [
        'name' => 'Viwemi Louis Chavula',
        'position' => 'Programs Subcommittee Chairperson',
        'image' => 'images/team/viwemi-chavula.jpg',
        'bio' => 'Human rights advocate with over 20 years of experience in democratic governance and women\'s empowerment.',
        'linkedin' => 'https://linkedin.com/in/grace-banda',
        'twitter' => 'https://twitter.com/drgraceb'
      ],
      [
        'name' => 'Jimmy Tsonga',
        'position' => 'Finance Subcommittee Chairperson',
        'image' => 'images/team/jimmy-tsonga.jpg',
        'bio' => 'Chartered Accountant with expertise in grants and finance management.',
        'linkedin' => 'https://linkedin.com/in/grace-banda',
        'twitter' => 'https://twitter.com/drgraceb'
      ],
      [
        'name' => 'Baxton Nkhoma',
        'position' => 'Secretary to the Board',
        'image' => 'images/team/baxton-nkhoma.jpg',
        'bio' => 'Governance and human rights activist with a focus on policy advocacy and institutional development.',
        'linkedin' => 'https://linkedin.com/in/grace-banda',
        'twitter' => 'https://twitter.com/drgraceb'
      ],
    ];
  }

  private function getTimeline(): array
  {
    return [
      [
        'year' => '2012',
        'title' => 'Foundation',
        'description' => 'Citizen Alliance was established with a vision to empower citizens and strengthen democratic governance in Malawi.'
      ],
      [
        'year' => '2015',
        'title' => 'First Major Initiative',
        'description' => 'Launched our flagship Citizen Voice program, reaching over 10,000 citizens across five districts.'
      ],
      [
        'year' => '2018',
        'title' => 'National Recognition',
        'description' => 'Received the National Civil Society Excellence Award for our community engagement work.'
      ],
      [
        'year' => '2020',
        'title' => 'Digital Transformation',
        'description' => 'Introduced digital platforms to enhance citizen participation and feedback mechanisms.'
      ],
      [
        'year' => '2023',
        'title' => 'Regional Expansion',
        'description' => 'Extended our programs to all regions of Malawi, impacting over 50,000 citizens.'
      ],
      [
        'year' => '2025',
        'title' => 'Strategic Vision',
        'description' => 'Launched our 2025-2030 strategic plan focusing on sustainable development and digital inclusion.'
      ]
    ];
  }

  private function getPartners(): array
  {
    return [
      [
        'name' => 'United Nations Development Programme (UNDP)',
        'logo' => 'images/partners/undp.png',
        'website' => 'https://undp.org',
      ],
      [
        'name' => 'European Union',
        'logo' => 'images/partners/eu.png',
        'website' => 'https://europa.eu',
      ],
      [
        'name' => 'USAID',
        'logo' => 'images/partners/usaid.png',
        'website' => 'https://usaid.gov',
      ],
      [
        'name' => 'Oxfam',
        'logo' => 'images/partners/oxfam.png',
        'website' => 'https://oxfam.org',
      ],
      [
        'name' => 'Royal Norwegian Embassy',
        'logo' => 'images/partners/norwegian-embassy.png',
        'website' => 'https://norway.no',
      ],
      [
        'name' => 'National Endowment for Democracy',
        'logo' => 'images/partners/ned.png',
        'website' => 'https://ned.org',
      ],
      [
        'name' => 'ActionAid Malawi',
        'logo' => 'images/partners/actionaid.png',
        'website' => 'https://actionaid.org',
      ],
      [
        'name' => 'NICE Trust',
        'logo' => 'images/partners/nice-trust.png',
        'website' => 'https://nicetrust.org',
      ],
    ];
  }
}
