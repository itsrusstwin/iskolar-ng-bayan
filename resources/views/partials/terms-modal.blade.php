<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold mb-0">Acknowledgment of Guidelines</h5>
            </div>
            <div class="modal-body">
                <p class="fw-semibold mb-1">Iskolar ng Bayan ng Santa Cruz Program</p>
                <p class="small text-muted-soft">As a scholar of the Iskolar ng Bayan ng Santa Cruz Program, I hereby acknowledge that:</p>

                <ol class="small lh-lg ps-4">
                    <li>I have attended/read/been oriented on the Scholarship Guidelines presented by the Local Youth Development Office (LYDO).</li>
                    <li>I fully understand the requirements and responsibilities as a beneficiary of this program, including but not limited to:
                        <ul class="ps-4 mt-1">
                            <li>Submission of Certified True Copy of report cards/grades every semester.</li>
                            <li>Compliance with the plastic waste contribution (10 kilos per semester).</li>
                            <li>Participation in mandatory activities (orientation, assemblies, youth programs, if any).</li>
                            <li>Maintenance of academic standing and adherence to the passing grade criteria.</li>
                            <li>Observation of proper behavior, discipline, and respect to program facilitators and co-scholars.</li>
                        </ul>
                    </li>
                    <li>I understand that failure to comply with the above requirements may lead to:
                        <ul class="ps-4 mt-1">
                            <li>Withholding of payout until compliance is met.</li>
                            <li>Suspension or termination of scholarship benefits.</li>
                            <li>Replacement by a waitlisted applicant.</li>
                        </ul>
                    </li>
                    <li>I commit to abide by the rules and regulations of the scholarship program throughout my period as a scholar.</li>
                </ol>

                <p class="small mb-0">By signing this document, I declare that I have read, understood, and voluntarily accepted the guidelines of the Iskolar ng Bayan ng Santa Cruz Program.</p>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <span class="small text-muted-soft d-none d-sm-inline">Accepted once on your first login.</span>
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3">I Disagree</button>
                    </form>
                    <form method="POST" action="{{ route('terms.accept') }}">
                        @csrf
                        <button type="submit" class="btn btn-brand px-4">I Agree</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
