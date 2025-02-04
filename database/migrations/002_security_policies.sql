-- Enable RLS
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE bookings ENABLE ROW LEVEL SECURITY;
ALTER TABLE hotel_bookings ENABLE ROW LEVEL SECURITY;
ALTER TABLE ticket_bookings ENABLE ROW LEVEL SECURITY;
ALTER TABLE educational_resources ENABLE ROW LEVEL SECURITY;
ALTER TABLE analytics ENABLE ROW LEVEL SECURITY;

-- Create policies
CREATE POLICY "Users can only view their own data"
    ON users FOR ALL
    USING (auth.uid() = id);

CREATE POLICY "Users can only access their own bookings"
    ON bookings FOR ALL
    USING (auth.uid() = user_id);

CREATE POLICY "Public can view educational resources"
    ON educational_resources FOR SELECT
    TO authenticated
    USING (true);

-- Add missing policies for hotel and ticket bookings
CREATE POLICY "Users can only access their hotel bookings"
    ON hotel_bookings FOR ALL
    USING (
        booking_id IN (
            SELECT id FROM bookings WHERE user_id = auth.uid()
        )
    );

CREATE POLICY "Users can only access their ticket bookings"
    ON ticket_bookings FOR ALL
    USING (
        booking_id IN (
            SELECT id FROM bookings WHERE user_id = auth.uid()
        )
    );

-- Add admin policy for analytics
CREATE POLICY "Only admins can access analytics"
    ON analytics FOR ALL
    USING (
        auth.uid() IN (
            SELECT id FROM users WHERE role = 'admin'
        )
    ); 