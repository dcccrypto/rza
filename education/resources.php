<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

// Define educational resources
$resources = [
    'conservation' => [
        'title' => 'Wildlife Conservation',
        'resources' => [
            [
                'title' => 'Protecting Endangered Species',
                'description' => 'Learn about our conservation efforts and how you can help protect endangered species.',
                'file' => 'endangered_species_guide.pdf',
                'icon' => 'bi-shield-fill'
            ],
            [
                'title' => 'Sustainable Zoo Practices',
                'description' => 'Discover how modern zoos contribute to wildlife conservation and sustainability.',
                'file' => 'sustainable_practices.pdf',
                'icon' => 'bi-recycle'
            ]
        ]
    ],
    'education' => [
        'title' => 'Animal Education',
        'resources' => [
            [
                'title' => 'Animal Habitats Guide',
                'description' => 'Explore different animal habitats and learn about their natural environments.',
                'file' => 'animal_habitats.pdf',
                'icon' => 'bi-tree-fill'
            ],
            [
                'title' => 'Animal Behavior Study',
                'description' => 'Understanding animal behavior and their social interactions.',
                'file' => 'animal_behavior.pdf',
                'icon' => 'bi-eye-fill'
            ]
        ]
    ],
    'activities' => [
        'title' => 'Kids Activities',
        'resources' => [
            [
                'title' => 'Zoo Adventure Workbook',
                'description' => 'Fun activities and worksheets for children to learn about animals.',
                'file' => 'kids_workbook.pdf',
                'icon' => 'bi-pencil-fill'
            ],
            [
                'title' => 'Animal Facts Cards',
                'description' => 'Printable cards with interesting facts about different animals.',
                'file' => 'animal_cards.pdf',
                'icon' => 'bi-card-text'
            ]
        ]
    ]
];

// Handle downloads (in a real environment, you'd want to validate user permissions and use proper file paths)
if (isset($_GET['download'])) {
    $file = trim($_GET['download']);
    $file_path = "../assets/pdfs/" . basename($file);
    
    if (file_exists($file_path)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        readfile($file_path);
        exit;
    }
}
?>

<div class="container my-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 mb-3">Educational Adventures at RZA</h1>
            <p class="lead">Discover, learn, and connect with wildlife through our interactive educational programs.</p>
        </div>
    </div>

    <!-- Educational Programs -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Our Educational Programs</h2>
            <div class="row g-4">
                <!-- Guided Tours -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-compass fs-3 text-success me-2"></i>
                                <h3 class="h5 mb-0">Guided Safari Tours</h3>
                            </div>
                            <p class="card-text">Join our expert guides for an educational journey through the zoo. Learn about animal behaviors, habitats, and conservation efforts.</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Available daily</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>45-minute duration</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Small groups of 10-15</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- School Visits -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-mortarboard fs-3 text-success me-2"></i>
                                <h3 class="h5 mb-0">School Programs</h3>
                            </div>
                            <p class="card-text">Tailored educational visits for school groups with hands-on activities and curriculum-linked workshops.</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Age-appropriate content</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Interactive workshops</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Teacher resource packs</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Conservation Workshops -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-tree fs-3 text-success me-2"></i>
                                <h3 class="h5 mb-0">Conservation Workshops</h3>
                            </div>
                            <p class="card-text">Hands-on sessions about wildlife conservation, environmental protection, and sustainability.</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Weekend sessions</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Practical activities</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Conservation projects</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Learning -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Interactive Learning</h2>
            <div class="row g-4">
                <!-- Wildlife Quiz -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-question-circle fs-3 text-success me-2"></i>
                                <h3 class="h5 mb-0">Wildlife Knowledge Quiz</h3>
                            </div>
                            <p class="card-text">Test your knowledge about wildlife, conservation, and animal habitats with our interactive quiz.</p>
                            <a href="quizzes.php" class="btn btn-success">Take Quiz</a>
                        </div>
                    </div>
                </div>

                <!-- Animal Guide -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-book fs-3 text-success me-2"></i>
                                <h3 class="h5 mb-0">Animal Guide</h3>
                            </div>
                            <p class="card-text">Download our comprehensive guide about the animals at RZA and their conservation status.</p>
                            <a href="?download=rza_animal_guide.pdf" class="btn btn-outline-success">
                                <i class="bi bi-download me-2"></i>Download Guide
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Programs -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Special Programs</h2>
            <div class="row g-4">
                <!-- Junior Zookeeper -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-badge fs-3 text-success me-2"></i>
                                <h3 class="h5 mb-0">Junior Zookeeper Program</h3>
                            </div>
                            <p class="card-text">A hands-on program for young animal enthusiasts to learn about animal care and conservation.</p>
                            <ul class="list-unstyled mb-3">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Ages 8-14</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Weekend sessions</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Certificate upon completion</li>
                            </ul>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#juniorZookeeperModal">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Photography Workshops -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-camera fs-3 text-success me-2"></i>
                                <h3 class="h5 mb-0">Wildlife Photography</h3>
                            </div>
                            <p class="card-text">Learn wildlife photography techniques from professional photographers in our specialized workshops.</p>
                            <ul class="list-unstyled mb-3">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>All skill levels</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Equipment provided</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Monthly sessions</li>
                            </ul>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#photographyModal">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Research Programs -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-microscope fs-3 text-success me-2"></i>
                                <h3 class="h5 mb-0">Research Opportunities</h3>
                            </div>
                            <p class="card-text">Participate in ongoing research projects and contribute to wildlife conservation efforts.</p>
                            <ul class="list-unstyled mb-3">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Student research projects</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Volunteer programs</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Collaboration opportunities</li>
                            </ul>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#researchModal">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Special Programs -->
<div class="modal fade" id="juniorZookeeperModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Junior Zookeeper Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Our Junior Zookeeper Program offers young animal enthusiasts a unique opportunity to learn about animal care, conservation, and zoo operations.</p>
                <h6>Program Details:</h6>
                <ul>
                    <li>6-week program (weekends only)</li>
                    <li>Hands-on experience with animal care</li>
                    <li>Learn from professional zookeepers</li>
                    <li>Conservation projects</li>
                    <li>Behind-the-scenes zoo access</li>
                </ul>
                <p>For enrollment and more information, please contact our education department.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="photographyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wildlife Photography Workshops</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Join our wildlife photography workshops and learn to capture stunning images of animals in their habitats.</p>
                <h6>Workshop Includes:</h6>
                <ul>
                    <li>Camera basics and advanced techniques</li>
                    <li>Wildlife photography tips</li>
                    <li>Early morning/sunset sessions</li>
                    <li>Equipment available for use</li>
                    <li>Photo editing tutorials</li>
                </ul>
                <p>Workshops are held monthly. Advance booking required.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="researchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Research Programs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>RZA offers various research opportunities for students and professionals interested in wildlife conservation.</p>
                <h6>Available Programs:</h6>
                <ul>
                    <li>Undergraduate research projects</li>
                    <li>Conservation studies</li>
                    <li>Behavioral research</li>
                    <li>Habitat monitoring</li>
                    <li>Species recovery programs</li>
                </ul>
                <p>Contact our research department to discuss potential collaboration opportunities.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 