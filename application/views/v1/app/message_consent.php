<style>
    .opt-in-card {
        max-width: 420px;
        background: white;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .form-check-label {
        font-size: 0.9rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
    }
</style>

<main>
    <div class="row">
        <div class="col-xs-12 column-flex-center">

            <!--  -->
            <div class="px-5 py-5">
                <div class="card opt-in-card">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Subscribe via SMS</h2>
                        <p class="text-muted small">Get real-time updates and alerts. No spam, ever.</p>
                    </div>

                    <?php if ($this->session->flashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('errors'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/messages/subscribe">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="+1 555-555-5555"
                                required>
                        </div>


                        <div class="mb-3 form-check">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" class="form-check-input me-3" id="consent" name="consent"
                                    required style="height: 35px !important; width: 10px !important;">
                                <label class="form-check-label mb-0" for="consent">
                                    I agree to receive SMS messages from this service. Message & data rates may apply.
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Subscribe Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</main>